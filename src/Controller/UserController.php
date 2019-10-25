<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-08-03
 * Time: 22:24
 */

namespace App\Controller;


use App\Entity\ClientContract;
use App\Entity\Contact;
use App\Entity\Invoice;
use App\Entity\Timesheet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class UserController extends AbstractController
{
    private $entitymanager;
    private $user;
    private $firstname;
    private $lastname;
    private $userid;
    private $usercontact;


    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entitymanager = $entityManager;
        $this->user = $security->getToken()->getUser()->getEmail();
        $this->usercontact = $security->getToken()->getUser()->getContact();
        $this->userid = $security->getToken()->getUser()->getId();
        $this->firstname = $security->getToken()->getUser()->getFirstName();
        $this->lastname = $security->getToken()->getUser()->getLastName();

    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="/user/dashboard", name="user_dashboard")
     */
    public function viewDashboard()
    {
        /**
         * TODO Add query to get contact details (address, tel etc...)
         */

        //Query to get all related connected user

        $personaldetails = $this->entitymanager
                                ->getRepository(Contact::class)
                                ->findBy(['id' => $this->usercontact]);

        $timesheet = $this->entitymanager
                          ->getRepository(Timesheet::class)
                          ->findBy(['user' => $this->user]);

        $clientcontract = $this->entitymanager
                               ->getRepository(ClientContract::class)
                               ->findBy(['user'=> $this->userid]);

        $invoice = $this->entitymanager
                        ->getRepository(Invoice::class)
                        ->findBy(['user' => $this->user]);

        return $this->render('user/dashboard.html.twig', [

            'timesheet' => $timesheet,
            'firsname' => $this->firstname,
            'lastname' => $this->lastname,
            'clientcontract' => $clientcontract,
            'invoice' => $invoice,
            'personaldetails' => $personaldetails
        ]);
    }


    public function listOfUsers()
    {
        /**
         * TODO add list of users
         */
    }

    public function editUser()
    {
        /**
         * TODO add modification of users
         */
    }

    public function deletUser()
    {
        /**
         * TODO add deletion of users
         */
    }


}