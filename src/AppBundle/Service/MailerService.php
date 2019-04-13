<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-04-05
 * Time: 21:34
 */

namespace AppBundle\Service;

use AppBundle\Entity\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailerService
{

    private $emailtocheckrep;

    private $mailrecipient;

    private $mailer;

    private $templating;

    const LOGO = '/../../../web/img/Logo_Senso_email.png';


    public function __construct(EntityManagerInterface $entityManager,
                                \Swift_Mailer $mailer,
                                ContainerInterface $container)
    {
        $this->emailtocheckrep = $entityManager->getRepository(Mail::class);

        $this->mailer = $mailer;

        $this->templating = $container->get('twig');

    }


    public function mailSender($mail, $messagebody, $messagesubject)
    {

        $message = \Swift_Message::newInstance('Info')
            ->setFrom(['info@senso.lu' => 'Info Senso'])
            ->setTo($mail)
            ->setSubject($messagesubject)
            ->setBody($messagebody, 'text/html')
            ->setCharset('UTF-8');

        $this->mailer->send($message);

    }


    public function sendMail($messagebody,$messagesubject){

        $this->mailrecipient = 'smouheb@senso.lu';

        $this->mailSender($this->mailrecipient, $messagebody, $messagesubject);

    }

    public function sendConfirmation($firstname, $lastname, $mail){

        $message = \Swift_Message::newInstance('Info')
            ->setFrom(['info@senso.lu' => 'Info Senso'])
            ->setTo($mail)
            ->setSubject('Confirmation');

        $image = $message->embed(\Swift_EmbeddedFile::fromPath(__DIR__.self::LOGO));

        $messagebody = $this->templating->render('mail_template/confirmation.html.twig', [

            'firstname'=>$firstname,
            'lastname' =>$lastname,
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
}