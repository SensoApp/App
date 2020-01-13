<?php


namespace App\Events;


use App\Entity\InvoiceRandom;
use Symfony\Contracts\EventDispatcher\Event;

class InvoiceRandomEvent extends Event
{
    public const NAME = 'invoiceRandom.creation';
    protected $invoiceRandom;

    public function __construct(InvoiceRandom $invoiceRandom)
    {
        $this->invoiceRandom = $invoiceRandom;
    }

    public function getInvoiceCreationDataId()
    {
        return $this->invoiceRandom->getId();
    }

}