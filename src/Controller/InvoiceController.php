<?php
/**
* Created by PhpStorm.
 * User: MacBookAir
* Date: 2019-09-26
* Time: 11:28
*/

namespace App\Controller;


use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class InvoiceController extends AbstractController
{

    private $security;
    private $invoice;
    private $entitymanager;

    const INVOICE_VALIDATED = 'Validated';
    const EDIT_INVOICE = 'edit';

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {

        $this->security = $security;
        $this->invoice = new Invoice();
        $this->entitymanager = $entityManager;
    }

    public function validateInvoice()
    {

    }

    public function deleteInvoice()
    {

    }

    public function editInvoice()
    {

    }

    public function viewInvoice()
    {

    }

    public function generateManualInvoice()
    {

    }

}