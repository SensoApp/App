<?php


namespace App\Events;


use App\Entity\InvoiceCreationData;
use Symfony\Contracts\EventDispatcher\Event;

class InvoiceManualCreationEvent extends Event
{

    public const NAME = 'invoice.creation';
    protected $invoiceCreationData;

    public function __construct(InvoiceCreationData $invoiceCreationData)
    {
        $this->invoiceCreationData = $invoiceCreationData;
    }

    public function getInvoiceCreationDataId()
    {
        return $this->invoiceCreationData->getId();
    }
}