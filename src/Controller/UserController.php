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
use App\Entity\StatementFile;
use App\Entity\Timesheet;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\ExcelGeneratorReport;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    private $statement;
    /**
     * @var ExcelGeneratorReport
     */
    private $excelGeneratorReport;


    public function __construct(EntityManagerInterface $entityManager, Security $security, ExcelGeneratorReport $excelGeneratorReport)
    {
        $this->entitymanager = $entityManager;
        $this->user = $security->getToken()->getUser()->getEmail();
        $this->usercontact = $security->getToken()->getUser()->getContact();
        $this->userid = $security->getToken()->getUser()->getId();
        $this->firstname = $security->getToken()->getUser()->getFirstName();
        $this->lastname = $security->getToken()->getUser()->getLastName();

        $this->excelGeneratorReport = $excelGeneratorReport;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="/user/dashboard", name="user_dashboard")
     */
    public function viewDashboard(Request $request, PaginatorInterface $paginator)
    {
        $this->statement = $this->selectStatementWithConditions($request);

        $pagination = $paginator->paginate($this->statement, $request->query->getInt('page', 1), 10 );

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
            'statementsum' => $statementsum
        ]);
    }

    /**
     * @Route(path="/user/dashboard/download", name="downloadstatement")
     */
    public function downloadExcelReport(Request $request)
    {
        $stmt = $this->entitymanager
             ->getRepository(StatementFile::class)
             ->selectPerOpertionsRef($request->request);

        $writer = $this->excelGeneratorReport->generateStatementInExcel($stmt);

        $response = $this->file($writer);

        $response->deleteFileAfterSend(true);

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

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();

            //$task->setUpdatedAt(new \DateTime('now'));

            $this->entitymanager->persist($task);

            //dd($this->entitymanager);

            $this->entitymanager->flush();

            $this->addFlash('success', 'Contact with id: ' . $id . ' has been successfully updated');

            return $this->redirectToRoute('adminsenso');

        }

        return $this->render('registration/register.edit.html.twig', [

            'registrationForm' => $form->createView()
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

            echo $exception->getMessage();
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

    protected function selectStatementWithConditions($request)
    {
        $minamount =  $request->request->get('Min-amount');
        $maxamount = $request->request->get('Max-amount');
        $mindate = $request->request->get('Min-date');
        $maxdate = $request->request->get('Max-date');

        if(!empty($minamount) && !empty($maxamount) || !empty($mindate) && !empty($maxdate) ){

            return $this->entitymanager
                        ->getRepository(StatementFile::class)
                        ->searchByCriterion($request, $this->userid );

        }

        return $this->entitymanager
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
        return $this->entitymanager
            ->getRepository(Invoice::class)
            ->findBy(['user' => $this->user]);
    }


}