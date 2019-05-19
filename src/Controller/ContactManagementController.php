<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-05-04
 * Time: 14:29
 */

namespace App\Controller;


use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ContactType;
use Symfony\Component\Routing\Annotation\Route;

class ContactManagementController extends AbstractController
{
    private $contact;
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->contact = new Contact();
        $this->em = $entityManager;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/newadmin/createcontact", name="createcontact" )
     */
    public function createContact(Request $request){

        $form = $this->createForm(ContactType::class, $this->contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $task = $form->getData();

            $this->em->persist($task);
            $this->em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('form/contact.html.twig', [

            'form' =>$form->createView()
        ]);
    }

    /**
     *@Route("/newadmin", name="adminsenso" )
     */
    public function listContact(){

        $list = $this->getDoctrine()->getRepository(Contact::class)->listOfAllContacts();

        return $this->render('form/admin.main.html.twig', [

            'list' => $list,

        ]);
    }

    /**
     * @Route("/newadmin/edit", name="edit")
     */
    public function editContact(){


    }

    /**
     * @Route("/newadmin/delete", name="delete")
     */
    public function deleteContact(){


    }

    /**
     * @Route("/newadmin/view/{id}", name="view")
     */
    public function viewContact($id){

        $list = $this->getDoctrine()->getRepository(Contact::class)->zoomInContact($id);


        return $this->render('form/viewcontact.html.twig', [

            'list' => $list
        ]);

    }

}