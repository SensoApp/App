<?php
/**
* Created by PhpStorm.
 * User: MacBookAir
* Date: 2019-09-26
* Time: 11:28
*/

namespace App\Controller;


use App\Entity\Invoice;
use App\Events\InvoiceValidationEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Security\Core\Security;

class InvoiceController extends AbstractController
{

    private $security;
    private $invoice;
    private $entitymanager;

    const INVOICE_VALIDATED = 'Validated - sent to client';
    const PAYMENT_PENDING = 'Unpaid';
    const PAYMENT_PAID = 'Paid';
    const INVOICE_CLOSED = 'Closed';

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {

        $this->security = $security;
        $this->invoice = new Invoice();
        $this->entitymanager = $entityManager;
    }

    /**
     * @Route(path="/user/validateinvoice/{id}", name="validateinvoice")
     */
    public function validateInvoice($id, EventDispatcherInterface $eventDispatcher)
    {

        $invoiceobject = $this->entitymanager
                              ->getRepository(Invoice::class)
                              ->find(['id' => $id]);


            $event = new InvoiceValidationEvent($invoiceobject);
            $eventDispatcher->dispatch(InvoiceValidationEvent::NAME, $event);

        // for all that create messengers and queues so that it can be done async

        $this->addFlash('success', 'the invoice has been successfully sent to the client');

        return $this->redirectToRoute('user_dashboard');
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

        try{

            $this->entitymanager->remove($invoicetodelete);
            $this->entitymanager->flush();

            $this->forward('App\Controller\TimesheetController::deleteTimesheet', [
                'id' => $invoicetodelete->getTimesheet()->getId()
            ]);

        } catch (\Exception $exception){

            echo $exception->getMessage();
        }

        unlink($filetodelete);


        $this->addFlash('success', 'Invoice with id: '.$id.' has been deleted successfully');

        return $this->redirectToRoute('user_dashboard');
    }

    /**
     *
     * @param Request $request
     *
     * @param $id
     *
     * @Route(path="/user/invoice/pdf/{id}", name="viewinvoicepdf")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewTimeSheetPdfInBrowser($id)
    {
        $path = $this->entitymanager
            ->getRepository(Invoice::class)
            ->find($id);

        return $this->file($path->getPath(), null, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    /**
     * @param Request $request
     *
     * @param $id
     *
     * @Route(path="/user/invoice/downloadpdf/{id}", name="downloadinvoice")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadTimeSheet($id)
    {
        $path = $this->entitymanager
            ->getRepository(Invoice::class)
            ->find($id);

        return $this->file($path->getPath());

    }

    /**
     * Show all the invoices per users/ employees with their status
     * @Route(path="/user/invoice/list", name="listofinvoice")
     */
    public function listOfInvoices()
    {
        $listofinvoices = $this->entitymanager->getRepository(Invoice::class)->selectInvoiceAndUserData();

        return $this->render('invoice/listOfInvoices.html.twig', [
            'invoice' => $listofinvoices
        ]);

    }

    /**
     * Validates that the payment has been received at the bank and update the status of related invoice
     * @Route(path="/user/invoice/paymentreceived/{id}", name="paymentreceived")
     */
    public function paymentReceivedValidation($id)
    {
        try{
            $this->entitymanager
                ->getRepository('App:Invoice')
                ->updateStatusAfterValidation(self::INVOICE_CLOSED, $id, self::PAYMENT_PAID);


        } catch (\Exception $exception){

             echo $exception->getMessage();

            $this->addFlash('error', 'the invoice has not been updated - please check');

        }

        $this->addFlash('success', 'the invoice has been updated');

        return $this->redirectToRoute('listofinvoice');

    }

    public function generateManualInvoice()
    {

    }

}