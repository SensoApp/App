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

        $form = $this->createForm(ContactType::class);

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
     * @Route(path="/newadmin", name="adminsenso" )
     */
    public function listContact(){

        $list = $this->getDoctrine()->getRepository(Contact::class)->listOfAllContacts();

        return $this->render('form/admin.html.twig', [

            'list' => $list,

        ]);
    }

    /**
     * @Route("/newadmin/edit/{id}", name="edit")
     */
    public function editContact(Request $request, $id, Contact $contact){

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $task = $form->getData();

            $task->setUpdatedAt(new \DateTime('now'));
            $this->em->flush($task);

            $this->addFlash('success','Contact with id: '.$id.' has been successfully updated');

            return $this->redirectToRoute('adminsenso');

        }


        return $this->render('form/edit.html.twig', [

            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/newadmin/delete/{id}", name="delete")
     */
    public function deleteContact($id){

        $contactToDelete = $this->getDoctrine()->getRepository(Contact::class)
                        ->findOneBy(['id' => $id]);

        if (!$contactToDelete) {

            throw $this->createNotFoundException('No Contact found for id '.$id);

        }

        $this->em->remove($contactToDelete);
        $this->em->flush();

        $this->addFlash('success','Contact successfully deleted');

        return $this->redirectToRoute('adminsenso');
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