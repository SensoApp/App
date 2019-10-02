<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-09-17
 * Time: 21:15
 */

namespace App\Events;


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

            TimeSheetValidationEvent::NAME => 'onTimesheetValidated'
        ];
    }


    public function onTimesheetValidated(TimeSheetValidationEvent $event)
    {
        //dd($event);
        $items = $this->invoiceRepository->findTimesheetAndContractForInvoice($event->getTimesheetId());

        $this->invoiceGenerator->retrieveDataForInvoice($items);

    }
}
