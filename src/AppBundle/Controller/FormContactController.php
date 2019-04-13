<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-04-04
 * Time: 21:57
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Mail;
use AppBundle\Entity\Phone;
use AppBundle\Service\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormContactController extends Controller
{
    /**
     * @param Request $request
     * @Route("/form", name="form")
     * @return
     */
    public function contactFormConsumer(Request $request,
                                        MailerService $mailerService)
    {

        $contact = new Contact();
        $contact->setFirstname($request->request->get('firstname'));
        $contact->setLastname($request->request->get('lastname'));

        $phone = new Phone();
        $phone->setPhonenumber($request->request->get('phone'));
        $phone->setContact($contact);

        $mail = new Mail();
        $mail->setMail($request->request->get('email'));
        $mail->setContact($contact);

        if($mailerService->checkMail($mail->getMail()) == false){

            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->persist($contact);
            $entitymanager->persist($mail);
            $entitymanager->persist($phone);
            $entitymanager->flush();
        }

        $messagebody = $request->request->get('message');
        $messagesubject = $request->request->get('subject');
        $mailerService->sendMail(
                                 $messagebody,
                                 $messagesubject
                                );

        $mailerService->sendConfirmation(
                        $contact->getFirstname(),
                        $contact->getLastname(),
                        $mail->getMail()
                        );

        $this->addFlash('success', 'Message submitted');

        return new Response('OK');

    }

}