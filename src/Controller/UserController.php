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
use App\Entity\InvoiceRandom;
use App\Entity\StatementFile;
use App\Entity\Timesheet;
use App\Entity\User;
use App\Form\EditRegistrationType;
use App\Form\ResetPasswordType;
use App\Service\ExcelGeneratorReport;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;


class UserController extends AbstractController
{
    private $entitymanager;
    private $user;
    private $firstname;
    private $lastname;
    private $userid;
    private $usercontact;
    private $statement;
    private $excelGeneratorReport;
    private $usersession;
    private $cache;


    public function __construct(EntityManagerInterface $entityManager, Security $security, ExcelGeneratorReport $excelGeneratorReport, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entitymanager = $entityManager;
        $this->user = $security->getToken()->getUser()->getEmail();
        $this->usersession = $security->getToken()->getUser();
        $this->usercontact = $security->getToken()->getUser()->getContact();
        $this->userid = $security->getToken()->getUser()->getId();
        $this->firstname = $security->getToken()->getUser()->getFirstName();
        $this->lastname = $security->getToken()->getUser()->getLastName();
        $this->cache = new FilesystemAdapter();



        $this->excelGeneratorReport = $excelGeneratorReport;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="/user/dashboard", name="user_dashboard")
     */
    public function viewDashboard(Request $request, PaginatorInterface $paginator)
    {
        if($request->request->count() > 0 || is_null($request->query->get('page'))){

            $this->cache->delete('query.sma');

            $this->statement = $this->selectStatementWithConditions($request);
            try {
                $val = $this->cache->getItem('query.sma');
                $val->set($this->statement);
                $this->cache->save($val);

            } catch (InvalidArgumentException $e) {

                return new Response($e->getMessage());
            }

            $pagination = $paginator->paginate($this->statement, $request->query->getInt('page', 1), 10 );

        } else {

            $pr = $this->cache->getItem('query.sma');

            $pagination = $paginator->paginate($pr->get(), $request->query->getInt('page', 1), 10 );
        }

        $statementsum = $this->entitymanager
                             ->getRepository(StatementFile::class)
                             ->selectSumPerUserStaement($this->userid);

        return $this->render('user/dashboard.html.twig', [

            'timesheet' => $this->selectTimesheet(),
            'firsname' => $this->firstname,
            'lastname' => $this->lastname,
            'clientcontract' => $this->selectClientContract(),
            'invoice' => $this->selectInvoice(),
            'personaldetails' => $this->selectPersonalDetails(),
            'pagination' => $pagination,
            'statementsum' => $statementsum,
            'users' => $this->usersession
        ]);
    }

    /**
     * @Route(path="/user/dashboard/download", name="downloadstatement")
     */
    public function downloadExcelReport(Request $request)
    {
        $pr = $this->cache->getItem('query.sma');

        $writer = $this->excelGeneratorReport->generateStatementInExcel($pr->get());

        $response = $this->file($writer);

        $response->deleteFileAfterSend(true);

        $this->cache->delete('query.sma');

        return $response;
    }


    /**
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route(path="/newadmin/edituser/{id}", name="edituser")
     */
    public function editUser(Request $request, $id,  User $user)
    {
        /**
         * TODO add modification of users
         */

        $form = $this->createForm(EditRegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();

            $task->setUpdatedAt(new \DateTime('now'));
            $this->entitymanager->persist($task);
            $this->entitymanager->flush();

            $this->addFlash('success', 'Contact with id: ' . $id . ' has been successfully updated');

            return $this->redirectToRoute('adminsenso');
        }
        return $this->render('registration/register.edit.html.twig', [

            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route(path="/user/password-reset/{id}", name="resetPassword")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserInterface $user)
    {
        $form = $this->createForm(ResetPasswordType::class, $this->usersession);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $request->request->get('reset_password')['OldPassword'];
            $newPassword = $request->request->get('reset_password')['plainPassword']['first'];

            try{
                if($passwordEncoder->isPasswordValid($user,$oldPassword)){
                    $newPasswordEncoder = $passwordEncoder->encodePassword($user, $newPassword);

                    $this->usersession->setPassword($newPasswordEncoder);
                    $this->usersession->setUpdatedAt(new \DateTime('now'));
                    $this->entitymanager->persist($user);
                    $this->entitymanager->flush();

                    $this->addFlash('success', 'Your password has been successfully updated');
                    return $this->redirectToRoute('user_dashboard');
                }

                $this->addFlash('error', 'password is not valid');
                return $this->redirectToRoute('resetPassword');

            } catch (\Exception $e){

                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('resetPassword');

            }
        }
        return $this->render('registration/register.resetPassword.html.twig', [
            'resetpassword' => $form->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route(path="/newadmin/deleteuser/{id}", name="deleteuser")
     */
    public function deletUser($id)
    {
        $usertodelete = $this->entitymanager
            ->getRepository(User::class)
            ->find($id);

        try{

            $this->entitymanager->remove($usertodelete);
            $this->entitymanager->flush();

        } catch (\Exception $exception){

            $this->addFlash('error',$exception->getMessage() );
            return $this->redirectToRoute('adminsenso');

        }

        $this->addFlash('success', 'User deleted successfully');
        return $this->redirectToRoute('adminsenso');
    }

    protected function returnAnArrayForRequest($request) :? array
    {
        if(count($request->request) > 0){

            foreach ($request->request as $key=>$value){

                $data[] = [$key => $value];
            }

            return $data;
        }

        return null;
    }

    /**
     * @param $request
     * @return mixed
     * @todo to refactor and create a repository used by other classes such as admin statement
     */
    protected function selectStatementWithConditions($request)
    {

        $minamount =  $request->request->get('Min-amount');
        $maxamount = $request->request->get('Max-amount');
        $mindate = $request->request->get('Min-date');
        $maxdate = $request->request->get('Max-date');

        if(!empty($minamount) && !empty($maxamount) || !empty($mindate) && !empty($maxdate) ){

            return  $this->entitymanager
                        ->getRepository(StatementFile::class)
                        ->searchByCriterion($request, $this->userid );
        }


        return  $this->entitymanager
                    ->getRepository(StatementFile::class)
                    ->selectAllForPagination($this->userid);

    }

    protected function selectPersonalDetails()
    {
        return $this->entitymanager
            ->getRepository(Contact::class)
            ->findBy(['id' => $this->usercontact]);
    }

    protected function selectTimesheet()
    {
       return $this->entitymanager
            ->getRepository(Timesheet::class)
            ->findBy(['user' => $this->user]);
    }

    protected function selectClientContract()
    {
        return  $this->entitymanager
            ->getRepository(ClientContract::class)
            ->findBy(['user'=> $this->userid]);
    }

    protected function selectInvoice()
    {
        $bigArr = ['Invoice' => $this->entitymanager
                                    ->getRepository(Invoice::class)
                                    ->findBy(['user' => $this->user]),
                    'Random' => $this->entitymanager
                                     ->getRepository(InvoiceRandom::class)
                                     ->findBy(['user' => $this->userid])
                    ];

        $collection = new ArrayCollection();

        foreach ($bigArr as $key => $bigA){

            foreach ($bigA as $arr){

                if ($key === 'Invoice'){

                    $collection->add( [
                                        'month'             =>$arr->getMonth(),
                                        'date'              => $arr->getDate(),
                                        'status'            => $arr->getStatus(),
                                        'paymentstatus'     => $arr->getPaymentstatus(),
                                        'totalamount'       => $arr->getTotalAmount(),
                                        'vatamount'         => $arr->getVatamount(),
                                        'amountttc'         => $arr->getAmountttc(),
                                        'id'                => $arr->getId(),
                                        'path'              => $arr->getPath(),
                                        'type'              => 'Invoice Consulting'

                                ]
                            );
                } else {

                    $vatamount = $arr->getVat() * $arr->getAmount();
                    $ttcAmount = $arr->getAmount() + $vatamount;
                    $dateMonth = $arr->getCreatedAt()->format('M');

                    $collection->add([

                                        'paymentstatus' => $arr->getPaymentStatus(),
                                        'totalamount'   => $arr->getAmount(),
                                        'vatamount'     => $vatamount,
                                        'amountttc'     => $ttcAmount,
                                        'status'        => $arr->getStatus(),
                                        'id'            => $arr->getId(),
                                        'month'         => $dateMonth,
                                        'date'          => $arr->getCreatedAt(),
                                        'path'          => $arr->getPath(),
                                        'type'          => 'Random Invoice'
                                ]
                    );

                }

            }

        }

        return $collection->getValues();
    }
}