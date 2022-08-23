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


class TransactionController extends AbstractController
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
     * @Route(path="/newadmin/transactions-summary", name="transactionAdmin")
     */
    public function transactionSummary(Request $request, PaginatorInterface $paginator)
    {
        // Balance per User
        $accounts = $this->getRevolutAccounts();
        $users = $this->entitymanager
                      ->getRepository(User::class)
                      ->findAllNoneAdminUsers();

        for ($i=0; $i < count($accounts); $i++)
        {
            $key = array_search($accounts[$i]->id, array_column($users, "revolutAccountId"));

            if($key)
            {
                $accounts[$i]->firstName = $users[$key]["firstname"];
                $accounts[$i]->lastName = $users[$key]["lastname"];
            }
        }

        // Transactions
        $transactions = $this->getRevolutTransactions();

        for ($j=0; $j < count($transactions->content); $j++)
        {
            $key = array_search($transactions->content[$j]->legs[0]->accountId, array_column($users, "revolutAccountId"));

            if($key)
            {
                $transactions->content[$j]->firstName = $users[$key]["firstname"];
                $transactions->content[$j]->lastName = $users[$key]["lastname"];
            }
        }
//        dump($transactions->content); die();

        return $this->render('form/admin-transaction.html.twig', [
            'accounts' => $accounts,
            'transactions' => $transactions->content
        ]);
    }

    public function getRevolutAccounts(): array {
        $accounts_response = $this->client->request(
            'GET',
            $this->params->get('app.senso_api_revolut').'/accounts'
        );

        return json_decode($accounts_response->getContent());
    }

    public function getRevolutTransactions() {
        $transactions_response = $this->client->request(
            'GET',
            $this->params->get('app.senso_api_revolut').'/transactions'
        );

        return json_decode($transactions_response->getContent());
    }

}