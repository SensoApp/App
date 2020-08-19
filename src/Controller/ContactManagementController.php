<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-05-04
 * Time: 14:29
 */

namespace App\Controller;


use App\Entity\Contact;
use App\Entity\ContactEndClient;
use App\Entity\User;
use App\Form\ContactEndClientType;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ContactType;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ClientContractType;
use App\Entity\ClientContract;


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
     * @Route("/newadmin/createcontact", name="createcontact" )
     */
    public function createContact(Request $request)
    {
        try {

            $form = $this->createForm(ContactType::class)
                ->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $task = $form->getData();
                $name = $task->getFirstName();

                try {

                    $this->em->persist($task);
                    $this->em->flush();
                    $this->addFlash('success', 'Contact ' . $name . ' created successfully');
                } catch (DBALException $DBALException) {
                    $this->addFlash('error', 'This Iban is already in user for another user');
                }

                return $this->redirectToRoute('adminsenso');
            }

        } catch (\Exception $e) {

            return $e->getMessage();
        }


        return $this->render('form/newcontact.html.twig', [

            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @Route(path="/newadmin/create-client", name="create_client")
     */
    public function createClientContact(Request $request)
    {
        $form = $this->createForm(ContactEndClientType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $clientcontact = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($clientcontact);
            $em->flush();


            $this->addFlash('success', sprintf('Client %s hase been created', $clientcontact->getClientName()));

            return $this->redirectToRoute('adminsenso');
        }

        return $this->render('form/contact-end-client.html.twig', [

            'form' => $form->createView()

        ]);
    }

    /**
     * @Route(path="/newadmin", name="adminsenso" )
     */
    public function listContact()
    {

        $list = $this->getDoctrine()->getRepository(Contact::class)->listOfAllContacts();


        $listofclients = $this->getDoctrine()->getRepository(ContactEndClient::class)->listOfAllClients();

        $listofcontracts = $this->getDoctrine()->getRepository(ClientContract::class)->findAll();


        $listofusers = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('form/admin.html.twig', [

            'list' => $list,
            'lists' => $listofclients,
            'users' => $listofusers,
            'clientcontract' => $listofcontracts

        ]);
    }

    /**
     * @Route("/newadmin/edit/{id}", name="edit")
     */
    public function editContact(Request $request, $id, Contact $contact)
    {

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();

            try {

                $task->setUpdatedAt(new \DateTime('now'));

                $this->em->flush();

                $this->addFlash('success', 'Contact with id: ' . $id . ' has been successfully updated');

                return $this->redirectToRoute('adminsenso');

            } catch (DBALException $DBALException) {

                $this->addFlash('error', 'This Iban you entered is already in use for another user');
                echo "IBAN incorrect";
            }


        }

        return $this->render('form/contactedit.html.twig', [

            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/newadmin/edit-client/{id}", name="edit_client")
     */
    public function editClientContact(Request $request, $id, ContactEndClient $contact)
    {
        $form = $this->createForm(ContactEndClientType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();

            $task->setUpdatedAt(new \DateTime('now'));

            $this->em->flush();

            $this->addFlash('success', 'Contact with id: ' . $id . ' has been successfully updated');

            return $this->redirectToRoute('adminsenso');

        }

        //dd($form);
        return $this->render('form/edit-client.html.twig', [

            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/newadmin/delete/{id}", name="delete")
     */
    public function deleteContact($id)
    {

        $contactToDelete = $this->getDoctrine()->getRepository(Contact::class)
            ->findOneBy(['id' => $id]);

        if (!$contactToDelete) {

            throw $this->createNotFoundException('No Contact found for id ' . $id);

        }

        $this->em->remove($contactToDelete);
        $this->em->flush();

        $this->addFlash('success', 'Contact successfully deleted');

        return $this->redirectToRoute('adminsenso');
    }


    /**
     * @Route("/newadmin/delete-client/{id}", name="delete_client")
     */
    public function deleteClient($id)
    {

        $contactToDelete = $this->getDoctrine()->getRepository(ContactEndClient::class)
            ->findOneBy(['id' => $id]);

        if (!$contactToDelete) {

            throw $this->createNotFoundException('No Contact found for id ' . $id);

        }

        $this->em->remove($contactToDelete);
        $this->em->flush();

        $this->addFlash('success', 'Contact successfully deleted');

        return $this->redirectToRoute('adminsenso');
    }

    /**
     * @Route("/newadmin/view/{id}", name="view")
     */
    public function viewContact($id)
    {

        $list = $this->getDoctrine()->getRepository(Contact::class)->zoomInContact($id);

        return $this->render('form/viewcontact.html.twig', [

            'list' => $list
        ]);

    }

    /**
     * @Route("/newadmin/view-client/{id}", name="view_client")
     */
    public function viewClient($id)
    {

        $list = $this->getDoctrine()->getRepository(ContactEndClient::class)->viewOneClient($id);


        return $this->render('form/view-client.html.twig', [

            'list' => $list
        ]);

    }

    /**
     * @Route(path="/newadmin/clientcontract", name="client_contract")
     */
    public function clientContractManagement(Request $request)
    {
        $clientcontractentity = new ClientContract();
        $form = $this->createForm(ClientContractType::class, $clientcontractentity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $clientcontract = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($clientcontract);

            $em->flush();

            $this->addFlash('success', 'Contract has been created successfully');

            //redirect to list of contract, add view and query to list thm all
            return $this->redirectToRoute('user_dashboard');

        }

        return $this->render('form/clientcontractmanagement.html.twig', [

            'form' => $form->createView()
        ]);

    }

    /**
     * @Route(path="/newadmin/clientcontract/edit/{id}", name="edit_client_contract")
     */
    public function editClientContractManagement(Request $request, $id, ClientContract $clientContract)
    {

        $form = $this->createForm(ClientContractType::class, $clientContract);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $clientcontract = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($clientcontract);

            $em->flush();

            $this->addFlash('success', 'Contract ' . $id . ' has been edited successfully');

            //redirect to list of contract, add view and query to list thm all
            return $this->redirectToRoute('user_dashboard');

        }

        return $this->render('form/clientcontractmanagement.edit.html.twig', [

            'form' => $form->createView()
        ]);

    }

    /**
     * @Route(path="/newadmin/clientcontract/delete/{id}", name="delete_client_contract")
     */
    public function deleteClientContractManagement(Request $request, $id)
    {

        $clientcontacttodelete = $this->em
            ->getRepository(ClientContract::class)
            ->find($id);

        try {

            $this->em->remove($clientcontacttodelete);
            $this->em->flush();

        } catch (\Exception $exception) {

            echo $exception->getMessage();
        }

        $this->addFlash('success', 'User deleted successfully');

        return $this->redirectToRoute('adminsenso');

    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @Route(path="/newadmin/clientcontract/active/{id}", name="active_client_contract")
     */
    public function activeClientContractManagement(Request $request, $id)
    {
        $act = $this->em->getRepository(ClientContract::class)->getActiveClient();
        $this->addFlash('success', 'User activated');

        return $this->redirectToRoute('adminsenso');
    }
}