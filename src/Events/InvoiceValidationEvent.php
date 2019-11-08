<?php


namespace App\Events;


use App\Entity\Invoice;
use Symfony\Component\EventDispatcher\Event;

class InvoiceValidationEvent extends Event
{
    public const NAME = 'invoice.validated';

    protected $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function getInvoiceId()
    {
        return $this->invoice->getId();
    }

}