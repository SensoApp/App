<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-04-05
 * Time: 21:34
 */

namespace App\Service;

use App\Entity\Contact;
use App\Entity\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Attachment;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerService
{

    private $emailtocheckrep;

    private $mailrecipient;

    private $mailer;

    private $templating;

    private $path;

    private $entityManager;




    public function __construct(
                                EntityManagerInterface $entityManager,
                                \Swift_Mailer $mailer,
                                ContainerInterface $container,
                                ParameterBagInterface $params
                                )
    {
        $this->emailtocheckrep = $entityManager->getRepository(Mail::class);

        $this->entityManager = $entityManager;

        $this->mailer = $mailer;

        $this->templating = $container->get('twig');

       $this->path = $params->get('kernel.project_dir').'/public/img/Logo_Senso_3.png';

    }


    public function mailSender($mail, $messagebody, $messagesubject, $attach = null)
    {
        //dd($mail, $messagebody, $messagesubject, $attach);
        $message = (new \Swift_Message('Info'))
            ->setFrom(['info@senso.lu' => 'Info Senso no-reply'])
            ->setTo($mail)
            ->setBcc('smouheb@senso.lu')
            ->setSubject($messagesubject)
            ->setBody($messagebody, 'text/html')
            ->setCharset('UTF-8');
        if($attach !== null){

            $message->attach(Swift_Attachment::fromPath($attach));

            try {

                $this->mailer->send($message);

            } catch(\Exception $exception) {
                 dd($exception->getMessage());
            }
        }
        $this->mailer->send($message);
    }


    public function sendMail($messagebody,$messagesubject, $attach = null){

        $this->mailrecipient = 'smouheb@senso.lu';

        $this->mailSender($this->mailrecipient, $messagebody, $messagesubject, $attach);


    }

    public function sendConfirmation($firstname, $lastname, $mail){

        $message = (new \Swift_Message('Info'))
            ->setFrom(['info@senso.lu' => 'Info Senso'])
            ->setTo($mail)
            ->setSubject('Confirmation');

        $image = $message->embed(\Swift_EmbeddedFile::fromPath($this->path));

        $messagebody = $this->templating->render('mail_template/confirmation.html.twig', [

            'firstname'=>$firstname,
            'lastname' =>$lastname,
            'image' => $image
        ]);

        $message->setBody($messagebody, 'text/html')
                ->setCharset('UTF-8');

        $this->mailer->send($message);

    }

    public function sendSimulation($firstname, $lastname, $mail, $attach)
    {

        $message = (new \Swift_Message('Info'))
            ->setFrom(['info@senso.lu' => 'Info Senso'])
            ->setTo($mail)
            ->setSubject('Simulation - SENSO SARL');

        $image = $message->embed(\Swift_EmbeddedFile::fromPath($this->path));

        $messagebody = $this->templating->render('mail_template/simulation.html.twig', [

            'firstname'=>$firstname,
            'lastname' =>$lastname,
            'image' => $image
        ]);

        //pass the path
        $message->attach(Swift_Attachment::fromPath($attach));

            try {

                $message->setBody($messagebody, 'text/html')
                    ->setCharset('UTF-8');

                $this->mailer->send($message);

            } catch(\Exception $exception) {
                dd($exception->getMessage());
            }
    }

    public function sendNewContactSimulation($firstname, $lastname, $email) : void
    {
        $message = (new \Swift_Message('Info'))
            ->setFrom(['info@senso.lu' => 'Info Senso'])
            ->setTo('info@senso.lu')
            ->setSubject('[Sales] New contact from simulation tool');

        $image = $message->embed(\Swift_EmbeddedFile::fromPath($this->path));

        $messagebody = $this->templating->render('mail_template/contactFromSimulation.html.twig', [

            'firstname'=>$firstname,
            'lastname' =>$lastname,
            'email' =>$email,
            'image' => $image
        ]);

        $message->setBody($messagebody, 'text/html')
            ->setCharset('UTF-8');

        $this->mailer->send($message);
    }

    /**
     * @param $emailtocheck
     * @return bool
     */
    public function checkMail($emailtocheck){

        //to check whether the email address is already persisted

        $query =  $this->emailtocheckrep->findBy([
            'mail'=>$emailtocheck
        ]);

        return $query!= null?true:false;


    }

    /**
     * @param $mail
     * @param $messagebody
     * @param $messagesubject
     * @param array|null $attach
     * Send email with multiple attachements
     */
    public function sendMultipleAttachement($mail, $messagebody, $messagesubject, array $attach = null)
    {
        $message = (new \Swift_Message('Invoice'))
            ->setFrom(['info@senso.lu' => 'Info Senso no-reply'])
            ->setTo($mail)
            ->setBcc('smouheb@senso.lu')
            ->setSubject($messagesubject)
            ->setBody($messagebody, 'text/html')
            ->setCharset('UTF-8');

             if(count($attach) > 1 ){

                 foreach ($attach as $attachement){

                     $message->attach(Swift_Attachment::fromPath($attachement));
                 }

                 $this->mailer->send($message);
             }

             $this->mailer->send($message);
    }
}