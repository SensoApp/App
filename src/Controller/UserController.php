<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-08-03
 * Time: 22:24
 */

namespace App\Controller;


use App\Entity\ClientContract;
use App\Entity\Timesheet;
use App\Form\ClientContractType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;


class UserController extends AbstractController
{
    private $entitymanager;
    private $user;
    private $firstname;
    private $lastname;


    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entitymanager = $entityManager;
        $this->user = $security->getToken()->getUser()->getEmail();
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

        $timesheet = $this->entitymanager
                          ->getRepository(Timesheet::class)
                          ->findBy(['user' => $this->user]);

        return $this->render('user/dashboard.html.twig', [

            'timesheet' => $timesheet,
            'firsname' => $this->firstname,
            'lastname' => $this->lastname
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

    /**
     * @Route(path="/newadmin/clientcontract", name="client_contract")
     */
    public function clientContractManagement(Request $request)
    {
        $clientcontractentity = new ClientContract();
        $form = $this->createForm(ClientContractType::class, $clientcontractentity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $clientcontract = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($clientcontract);

            $em->flush();

            $this->addFlash('success', 'Contract has been created successfully');

            //redirect to list of contract, add view and query to list thm all
            return $this->redirectToRoute('user_dashboard');


        }

        return $this->render('user/clientcontractmanagement.html.twig', [

            'form' => $form->createView()
        ]);

    }

}