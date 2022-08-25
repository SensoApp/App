<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class StatementController extends AbstractController
{

    private $params;
    private $client;
    private $entitymanager;

    public function __construct(ContainerBagInterface $params, HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->params = $params;
        $this->client = $client;
        $this->entitymanager = $entityManager;
    }


    /**
     * @Route(path="/newadmin/statements-summary", name="statementAdmin")
     */
    public function statementSummary(Request $request, PaginatorInterface $paginator)
    {
        // Balance per User
        $accounts = $this->getRevolutAccounts();
        $users = $this->entitymanager
                      ->getRepository(User::class)
                      ->findAllNoneAdminUsers();

        for ($i=0; $i < count($accounts); $i++)
        {
            $key = array_search($accounts[$i]->id, array_column($users, "revolutAccountId"));

            if($key !== FALSE)
            {
                $accounts[$i]->firstName = $users[$key]["firstname"];
                $accounts[$i]->lastName = $users[$key]["lastname"];
            }
        }


        // Transactions
        $transactions = $this->getRevolutTransactions($request);

        for ($j=0; $j < count($transactions); $j++)
        {
            $key = array_search($transactions[$j]->legs[0]->accountId, array_column($users, "revolutAccountId"));

            if($key !== FALSE)
            {
                $transactions[$j]->firstName = $users[$key]["firstname"];
                $transactions[$j]->lastName = $users[$key]["lastname"];
            }
        }

        return $this->render('form/admin-statements.html.twig', [
            'accounts' => $accounts,
            'transactions' => $transactions
        ]);
    }

    public function getRevolutAccounts(): array {
        $accounts_response = $this->client->request(
            'GET',
            $this->params->get('app.senso_api_revolut').'/accounts'
        );

        return json_decode($accounts_response->getContent());
    }

    public function getRevolutTransactions(Request $request) {
        $accountId =  null;
        $minAmount =  $request->request->get('Min-amount');
        $maxAmount = $request->request->get('Max-amount');
        $minDate = $request->request->get('Min-date');
        $maxDate = $request->request->get('Max-date');

        $firstArg = true;
        $url = $this->params->get('app.senso_api_revolut').'/transactions?';

        if(!empty($request->request->get('firstName')) && !empty($request->request->get('lastName'))) {
            $user = $this->entitymanager
                 ->getRepository(User::class)
                 ->findByFullName($request->request->get('firstName'), $request->request->get('lastName'));

            if($user == null) {
                return [];
            }

            $accountId = $user->getRevolutAccountId();
        } elseif (!empty($request->request->get('firstName'))) {
            $user = $this->entitymanager
                ->getRepository(User::class)
                ->findByFirstName($request->request->get('firstName'));

            if($user == null) {
                return [];
            }

            $accountId = $user->getRevolutAccountId();
        } elseif (!empty($request->request->get('lastName'))) {
            $user = $this->entitymanager
                ->getRepository(User::class)
                ->findByLastName($request->request->get('lastName'));

            if($user == null) {
                return [];
            }

            $accountId = $user->getRevolutAccountId();
        }

        if(!empty($accountId))
        {
            $url.= "accountId=".$accountId;
            $firstArg = false;
        }

        if(!empty($minAmount))
        {
            if($firstArg) {
                $firstArg = false;
            } else {
                $url.='&';
            }

            $url.= "minAmount=".$minAmount;
        }

        if(!empty($maxAmount))
        {
            if($firstArg) {
                $firstArg = false;
            } else {
                $url.='&';
            }

            $url.= "maxAmount=".$maxAmount;
        }

        if(!empty($minDate))
        {
            if($firstArg) {
                $firstArg = false;
            } else {
                $url.='&';
            }

            $url.= "minDate=".$minDate;
        }

        if(!empty($maxDate))
        {
            if(!$firstArg) {
                $url.='&';
            }

            $url.= "maxDate=".$maxDate;
        }

        return json_decode($this->client->request('GET', $url)->getContent());
    }

}