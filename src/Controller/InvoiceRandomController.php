<?php


namespace App\Controller;


use App\Entity\Invoice;
use App\Entity\InvoiceRandom;
use App\Events\InvoiceRandomEvent;
use App\Events\InvoiceRandomValidationEvent;
use App\Form\InvoiceRandomType;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class InvoiceRandomController extends AbstractController
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

    public function __construct(Security $security, EntityManagerInterface $entityManager,  EventDispatcherInterface $eventDispatcher)
    {

        $this->security = $security;
        $this->invoice = new Invoice();
        $this->entitymanager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     * @Route(path="/user/invoice/save-random-invoice", name="randomInvoice")
     */
    public function createRandomInvoice(Request $request)
    {
        $form = $this->createForm(InvoiceRandomType::class)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            try {

                $invoiceRandomPersist = $form->getData();
                $invoiceRandomPersist->setStatus(self::INVOICE_CREATED);
                $em =$this->getDoctrine()->getManager();
                $em->persist($invoiceRandomPersist);
                $em->flush();

                $this->dispatchEventRandomInvoice($invoiceRandomPersist);
                $this->addFlash('success', 'Invoice can be found in the following page: ');

                return $this->redirectToRoute('randomInvoice');

            } catch(DBALException $e){
                echo 'DBAL '.$e->getMessage(); die;

            } catch(\Exception $e){
                echo $e->getMessage();
                die;
            }

        }
        return $this->render('invoice/createRandom-invoice.html.twig', [

            'createRandomInvoice' => $form->createView()
        ]);

    }

    /**
     * @Route(path="/newadmin/validate-random-invoice/{id}", name="validateRandomInvoice")
     */
    public function validateInvoice($id, EventDispatcherInterface $eventDispatcher)
    {

        $invoiceobject = $this->entitymanager
            ->getRepository(InvoiceRandom::class)
            ->find(['id' => $id]);

        $event = new InvoiceRandomValidationEvent($invoiceobject);
        $eventDispatcher->dispatch($event, InvoiceRandomValidationEvent::NAME );

        $this->addFlash('success', 'the invoice has been successfully sent to the client');

        return $this->redirectToRoute('listofinvoice');
    }

    /**
     * @Route(path="/newadmin/delete-random-invoice/{id}", name="deleteRandomInvoice")
     */
    public function deleteInvoice($id)
    {
        $invoicetodelete = $this->entitymanager
            ->getRepository(InvoiceRandom::class)
            ->find($id);

        $filetodelete = $invoicetodelete->getPath();

        try{
            $this->entitymanager->remove($invoicetodelete);
            $this->entitymanager->flush();

        } catch (\Exception $exception){

            echo $exception->getMessage();
        }

        unlink($filetodelete);


        $this->addFlash('success', 'Invoice with id: '.$id.' has been deleted successfully');

        return $this->redirectToRoute('listofinvoice');
    }

    /**
     *
     * @param Request $request
     *
     * @param $id
     *
     * @Route(path="/newadmin/random-invoice/pdf/{id}", name="viewrandominvoicepdf")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewInvoicePdfInBrowser($id)
    {
        $path = $this->entitymanager
            ->getRepository(InvoiceRandom::class)
            ->find($id);

        return $this->file($path->getPath(), null, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    /**
     * @param Request $request
     *
     * @param $id
     *
     * @Route(path="/newadmin/random-invoice/downloadpdf/{id}", name="downloadinvoice")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadInvoice($id)
    {
        $path = $this->entitymanager
            ->getRepository(InvoiceRandom::class)
            ->find($id);

        return $this->file($path->getPath());

    }


    /**
     * Validates that the payment has been received at the bank and update the status of related invoice
     * @Route(path="/newadmin/random-invoice/paymentreceived/{id}", name="paymentreceived")
     */
    public function paymentReceivedValidation($id)
    {
        try{
            $this->entitymanager
                ->getRepository('App:InvoiceRandom')
                ->updateStatusAfterValidationRandomInvoice(self::INVOICE_CLOSED, $id, self::PAYMENT_PAID);


        } catch (\Exception $exception){

            echo $exception->getMessage();

            $this->addFlash('error', 'the invoice has not been updated - please check');

        }

        $this->addFlash('success', 'the status has been updated');

        return $this->redirectToRoute('listofinvoice');
    }

    /**
     * @param $invoiceObject
     * Method to dispatch the event for random invoices
     */
    protected function dispatchEventRandomInvoice($invoiceObject)
    {
        $event = new InvoiceRandomEvent($invoiceObject);
        $this->eventDispatcher->dispatch($event, InvoiceRandomEvent::NAME);

    }

}