<?php


namespace App\Events;


use App\Entity\InvoiceRandom;
use Symfony\Contracts\EventDispatcher\Event;

class InvoiceRandomValidationEvent extends Event
{
    public const NAME = 'Randominvoice.validated';

    protected $invoiceRandom;

    public function __construct(InvoiceRandom $invoiceRandom)
    {
        $this->invoiceRandom = $invoiceRandom;
    }

    public function getInvoiceId()
    {
        return $this->invoiceRandom->getId();
    }

}