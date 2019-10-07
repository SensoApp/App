<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-09-17
 * Time: 21:15
 */

namespace App\Events;


use App\Controller\InvoiceController;
use App\Entity\Invoice;
use App\Invoice\InvoiceGenerator;
use App\Repository\InvoiceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AppSubscriber implements EventSubscriberInterface
{


    private $invoiceRepository;
    private $invoiceGenerator;

    public function __construct(InvoiceRepository $invoiceRepository, InvoiceGenerator $invoiceGenerator)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceGenerator = $invoiceGenerator;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.

        return [

            TimeSheetValidationEvent::NAME => 'onTimesheetValidated',
            InvoiceValidationEvent::NAME => 'onInvoiceValidated'
        ];
    }


    public function onTimesheetValidated(TimeSheetValidationEvent $event)
    {
        $items = $this->invoiceRepository->findTimesheetAndContractForInvoice($event->getTimesheetId());

        $this->invoiceGenerator->retrieveDataForInvoice($items);

    }

    public function onInvoiceValidated(InvoiceValidationEvent $event)
    {
        $this->invoiceRepository
             ->updateStatusAfterValidation(
                                        InvoiceController::INVOICE_VALIDATED,
                                               $event->getInvoiceId(),
                                  InvoiceController::PAYMENT_PENDING
                                          );

        // send email to client (create process to get the email address of Client (Clientcontract)?
        // send email to accountant (subject Vente... and vente email address)
        // Add signed timesheet (path when uploded/validated is up to date i.e. timesheet_signed
    }
}
