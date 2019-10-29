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
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entitymanager = $entityManager;
        $this->user = $security->getToken()->getUser()->getEmail();
        $this->usercontact = $security->getToken()->getUser()->getContact();
        $this->userid = $security->getToken()->getUser()->getId();
        $this->firstname = $security->getToken()->getUser()->getFirstName();
        $this->lastname = $security->getToken()->getUser()->getLastName();

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="/user/dashboard", name="user_dashboard")
     */
    public function viewDashboard(Request $request, PaginatorInterface $paginator)
    {

        $statement = $this->selectStatementWithConditions($request);

        $pagination = $paginator->paginate($statement, $request->query->getInt('page', 1), 10 );

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