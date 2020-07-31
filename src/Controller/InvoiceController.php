<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-09-26
 * Time: 11:28
 */

namespace App\Controller;


use App\Entity\ClientContract;
use App\Entity\ContactEndClient;
use App\Entity\Contract;
use App\Entity\Invoice;
use App\Entity\InvoiceCreationData;
use App\Entity\InvoiceRandom;
use App\Events\InvoiceManualCreationEvent;
use App\Events\InvoiceRandomEvent;
use App\Events\InvoiceValidationEvent;
use App\Form\InvoiceManualCreationType;
use App\Form\InvoiceRandomType;
use App\Repository\ClientContractRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class InvoiceController extends AbstractController
{

    private $security;
    private $invoice;
    private $entitymanager;

    const INVOICE_VALIDATED = 'Validated - sent to client';
    const INVOICE_CREATED = 'Created';
    const PAYMENT_PENDING = 'Unpaid';
    const PAYMENT_PAID = 'Paid';
    const INVOICE_CLOSED = 'Closed';
    private $eventDispatcher;

    public function __construct(Security $security, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {

        $this->security = $security;
        $this->invoice = new Invoice();
        $this->entitymanager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route(path="/newadmin/validateinvoice/{id}", name="validateinvoice")
     */
    public function validateInvoice($id, EventDispatcherInterface $eventDispatcher)
    {

        $invoiceobject = $this->entitymanager
            ->getRepository(Invoice::class)
            ->find(['id' => $id]);


        $event = new InvoiceValidationEvent($invoiceobject);
        $eventDispatcher->dispatch($event, InvoiceValidationEvent::NAME);

        // for all that create messengers and queues so that it can be done async

        $this->addFlash('success', 'the invoice has been successfully sent to the client');

        return $this->redirectToRoute('listofinvoice');
    }

    /**
     * @Route(path="/newadmin/deleteinvoice/{id}", name="deleteinvoice")
     */
    public function deleteInvoice($id)
    {
        $invoicetodelete = $this->entitymanager
            ->getRepository(Invoice::class)
            ->find($id);

        $filetodelete = $invoicetodelete->getPath();

        try {
            $this->entitymanager->remove($invoicetodelete);
            $this->entitymanager->flush();

            /**
             * TODO : re-activate when Timeheet will be re-activated
             * TODO : Delete invoiceCreationData
             */
            /*$this->forward('App\Controller\TimesheetController::deleteTimesheet', [
                'id' => $invoicetodelete->getTimesheet()->getId()
            ]);*/

        } catch (\Exception $exception) {

            echo $exception->getMessage();
        }

        unlink($filetodelete);


        $this->addFlash('success', 'Invoice with id: ' . $id . ' has been deleted successfully');

        return $this->redirectToRoute('listofinvoice');
    }

    /**
     *
     * @param $id
     *
     * @return Response
     * @Route(path="/invoice/pdf/{id}", name="viewinvoicepdf")
     *
     */
    public function viewInvoicePdfInBrowser($id)
    {
        $path = $this->entitymanager
            ->getRepository(Invoice::class)
            ->find($id);

        return $this->file($path->getPath(), null, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    /**
     * @param $id
     * @return Response
     * @Route(path="/newadmin/invoice/downloadpdf/{id}", name="downloadinvoice")
     */
    public function downloadInvoice($id)
    {
        $path = $this->entitymanager
            ->getRepository(Invoice::class)
            ->find($id);

        return $this->file($path->getPath());

    }

    /**
     * Show all the invoices per users/ employees with their status
     * Add invoice from random table as well
     * @Route(path="/newadmin/invoice/list", name="listofinvoice")
     */
    public function listOfInvoices()
    {
        $listofinvoices = $this->entitymanager->getRepository(Invoice::class)->selectInvoiceAndUserData();
        $listofRandominvoices = $this->entitymanager->getRepository(InvoiceRandom::class)->selectRandomInvoiceAndUserData();


        return $this->render('invoice/listOfInvoices.html.twig', [
            'invoice' => $listofinvoices,
            'randominvoice' => $listofRandominvoices
        ]);

    }

    /**
     * Validates that the payment has been received at the bank and update the status of related invoice
     * @Route(path="/newadmin/invoice/paymentreceived/{id}", name="paymentreceived")
     * @param $id
     * @return RedirectResponse
     */
    public function paymentReceivedValidation($id)
    {
        try {
            $this->entitymanager
                ->getRepository('App:Invoice')
                ->updateStatusAfterValidation(self::INVOICE_CLOSED, $id, self::PAYMENT_PAID);


        } catch (\Exception $exception) {

            $this->addFlash('error', 'the invoice has not been updated - ' . $exception->getMessage());

        }

        $this->addFlash('success', 'the status has been updated');

        return $this->redirectToRoute('listofinvoice');

    }

    /**
     * controller to create a manual invoice
     * @param Request $request
     * @Route(path="/newadmin/invoice/save-manual-invoice", name="saveManualInvoice")
     * @return RedirectResponse|Response
     */
    public function createManualInvoice(Request $request)
    {

        $month = $request->request->get('month');

        $form = $this->createForm(InvoiceManualCreationType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $invoicePersist = $form->getData();
                $invoicePersist->setMonth($month);
                $entry = $this->checkEntryInvoice($invoicePersist->getMonth(), $invoicePersist->getUser()->getId());

                if (!$entry) {

                    $this->addFlash('error',
                        'an Invoice has already been created for this user ' . $invoicePersist->getUser()->getFirstname() . ' ' . $invoicePersist->getUser()->getLastname()
                    );

                    return $this->redirectToRoute('saveManualInvoice');
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($invoicePersist);
                $em->flush();

                $this->dispatchEventInvoice($invoicePersist);
                $this->addFlash('success', 'Invoice data entered can be found in the following link: ');

                return $this->redirectToRoute('saveManualInvoice');

            } catch (DBALException $e) {

                $this->addFlash('error', 'DBAL: ' . $e->getMessage());

            } catch (\Exception $e) {

                $this->addFlash('error', $e->getMessage());
            }
        }
        return $this->render('invoice/createinvoice.html.twig', [
            'createInvoice' => $form->createView()
        ]);

    }

    //internal methods

    /**
     * @param $invoiceObject
     * Method to dispatch the event for manual invoices
     */
    protected function dispatchEventInvoice($invoiceObject)
    {
        $event = new InvoiceManualCreationEvent($invoiceObject);
        $this->eventDispatcher->dispatch($event, InvoiceManualCreationEvent::NAME);

    }

    /**
     * Checks first whether a client contract has been created then whether an invoice has been created for the same period and same user
     * @param $date
     * @param $id
     * @return bool|RedirectResponse
     */
    protected function checkEntryInvoice($date, $id)
    {
        $contractId = $this->entitymanager
            ->getRepository(ClientContract::class)
            ->findBy(['user' => $id]);

        if (!empty($contractId)) {

            $query = $this->entitymanager
                ->getRepository(InvoiceCreationData::class)
                ->findBy([
                    'user' => $id,
                    'month' => $date
                ]);

            return empty($query);

        }
        $this->addFlash('error',
            'No client contract has been created for this user'
        );

        return $this->redirectToRoute('saveManualInvoice');

    }

    /**
     * @Route(path="/newadmin/invoice/showAllContract", name="showAllContract")
     * @param Request $request
     * @return JsonResponse
     */
    public function viewContract(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $template_id = $request->get('user');
        //dump($template_id);
        $getName = explode(" ", $template_id);
        $firstname = $getName[0];
        $lastname = $getName[1];
        $templateRepository = $entityManager->getRepository(ClientContract::class)->getListPerUser($firstname, $lastname);

        return new JsonResponse(json_encode($templateRepository));
    }

}