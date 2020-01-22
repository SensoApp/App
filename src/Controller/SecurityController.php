<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordForgottenType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [

            'last_username' => $lastUsername,
            'error' => $error

        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route(path="/password-forgotten", name="forgotPassword")
     */
    public function forgotPassword(
                                    Request $request,
                                    TokenGeneratorInterface $tokenGenerator,
                                    \Swift_Mailer $swift_Mailer
                                 )
    {
        if($request->isMethod('POST')){

            $email = $request->request->get('email');
            $emmailEntity = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $email]);
            $userObject =  $emmailEntity[0];

            if (!empty($emmailEntity)) {

                try {
                    $token = $tokenGenerator->generateToken();
                    $userObject->setResettoken($token);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($userObject);
                    $em->flush();

                    $url = $this->generateUrl('passwordForgottenSecond',
                        ['token' => $token],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );
                    $message = (new \Swift_Message('Password reset'))
                        ->setFrom('info@senso.lu')
                        ->setTo($email)
                        ->setBody(
                            "Please find the link below to reset your password: \n\n" . $url,
                            'text/html'
                        );
                    $swift_Mailer->send($message);
                    $this->addFlash('success', 'An email has been sent to you to reset your password');
                    return $this->redirectToRoute('app_login');

                } catch (\Exception $e) {

                    $this->addFlash('error', $e->getMessage() . ' ' . $e->getCode());
                    return $this->redirectToRoute('app_login');
                }

            } else {
                $this->addFlash('error', 'Email doesn not exist');
                return $this->redirectToRoute('app_login');
            }

        }
        return $this->render('security/passwordForgottenFirstStep.edit.html.twig');
    }

    /**
     * @Route(path="reset-forgotten-password", name="passwordForgottenSecond")
     */
    public function resetForgottenPassword(
                                            Request $request,
                                            UserPasswordEncoderInterface $passwordEncoder
                                        )
    {
        $token = $request->query->get('token');
        $userEntity = $this->getDoctrine()->getRepository(User::class)->findBy(['resetToken' => $token]);
        $user = $userEntity[0];

        if(!empty($userEntity)) {

            $form = $this->createForm(PasswordForgottenType::class, $user);
            $form->handleRequest($request);
            $newPassword = $request->request->get('password_forgotten')['plainPassword']['first'];

            if ($form->isSubmitted() && $form->isValid()) {

                try {
                    $newPasswordEncoded = $passwordEncoder->encodePassword($user, $newPassword);
                    $userEntity = $form->getData();

                    $userEntity->setPassword($newPasswordEncoded);
                    $userEntity->setResettoken(null);
                    $userEntity->setUpdatedat(new \DateTime('now'));
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($userEntity);
                    $em->flush();

                    $this->addFlash('success', 'Your password has been updated');
                    return $this->redirectToRoute('app_login');

                } catch (\Exception $e) {

                    $this->addFlash('error', $e->getMessage());
                    return $this->redirectToRoute('app_login');
                }
            }
            return $this->render('security/passwordForgottenSecondStep.edit.html.twig', [
                'passwordForgottenSecond' => $form->createView()
            ]);
        }
        $this->addFlash('error','Token is not recognized');
        return $this->redirectToRoute('app_login');
    }

}
