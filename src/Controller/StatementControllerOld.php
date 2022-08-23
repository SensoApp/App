<?php


namespace App\Controller;


use App\Entity\Contact;
use App\Entity\StatementFile;
use App\Entity\User;
use App\Form\StatementFileType;
use App\Service\MailerService;
use App\Service\UploadHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatementControllerOld extends AbstractController
{
    private $statement;
    private $cache;
    private $entityManager;
    private $mailerService;

    public function __construct(EntityManagerInterface $entityManager, MailerService $mailerService)
    {
        $this->cache = new FilesystemAdapter();
        $this->entityManager = $entityManager;
        $this->mailerService = $mailerService;
    }

    /**
     * @Route(path="/newadmin/uploadstatement", name="uploadstatement")
     */
    public function uploadCsvStatement(Request $request, UploadHelper $helper)
    {
            if(!is_null($request->files->get('csv_file'))){

                try {

                    $file = $helper->uploadStatement($request);

                    $query = $this->entityManager->getRepository(StatementFile::class)->searchByIbanStatement($file['info']);

                    if($request->request->get('send-email') === 'on' && $file['insertedLines'] > 0){

                        $email = $query[0]['mail'];
                        $firstName = $query[0]['firstname'];
                        $lastName = $query[0]['lastname'];

                        $messageBody = 'Dear '.$firstName.' '.$lastName.
                            '<br>'.'<br>'.
                            '<p>This is to notify you that your statement has been updated</p>'.
                            '<br>'.
                            '<p>Thanks</p>'.
                            '<br>'.
                            'The Senso Team';

                        $this->mailerService->sendMail($email,$messageBody, '[Notification] Statement update' );
                    }

                    $file['status'] === 'success' ? $this->addFlash('success', $file['message']) : $this->addFlash('error', $file['message']);

                    return $this->redirectToRoute('uploadstatement');

                } catch (Exception $e){

                    echo $e->getMessage();
                }
            }

            $data = $this->getDoctrine()
                        ->getRepository(StatementFile::class)
                        ->lastUploadedPerUserAndAccount();

        return $this->render('statement/uploadstatement.html.twig', [
            'data' => $data,

        ]);

    }

    /**
     * @Route(path="/newadmin/statements-summary-old", name="statementAdminOld")
     */
    public function viewBalancePerConsultant(Request $request, PaginatorInterface $paginator)
    {
        //liste des consultants  et leurs balances

        $query = $this->getDoctrine()
                      ->getRepository(StatementFile::class)
                      ->searchBalancePerConsultant();


        if($request->request->count() > 0 || is_null($request->query->get('page'))){

            $this->cache->delete('query.sma');

            $this->statement = $this->searchStatement($request);
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


        return $this->render('form/admin-statements.html.twig', [

            'Balance' => $query,
            'pagination' => $pagination

        ]);
        //recherche sur les statements
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="/newadmin/add-statement-entry", name="statementEntry")
     */
    public function addMovement(Request $request){

        $form = $this->createForm(StatementFileType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();

            $refMovement = uniqid().'_mnaual-entry';

            $data->setReferenceMovement($refMovement);

            $this->entityManager->persist($data);
            $this->entityManager->flush();

            if($request->request->get('send-email') === 'on'){

                $email = $data->getUser()->getEmail();
                $firstName = $data->getUser()->getFirstname();
                $lastName = $data->getUser()->getLastname();

                $messageBody = 'Dear '.$firstName.' '.$lastName.
                                '<br>'.'<br>'.
                                '<p>This is to notify you that your statement has been updated</p>'.
                                '<br>'.
                                '<p>Thanks</p>'.
                                '<br>'.
                                'The Senso Team';

                $this->mailerService->sendMail($email,$messageBody, '[Notification] Statement update' );
            }

            $this->addFlash('success', 'Movement added');

            return $this->redirectToRoute('statementAdmin');
        }

        return $this->render('form/add-manual-statements.html.twig', [

            'form' => $form->createView()

        ]);
    }

    public function searchStatement($request)
    {
        $minamount =  $request->request->get('Min-amount');
        $maxamount = $request->request->get('Max-amount');
        $mindate = $request->request->get('Min-date');
        $maxdate = $request->request->get('Max-date');

        if(!empty($minamount) && !empty($maxamount) || !empty($mindate) && !empty($maxdate) ){

            return  $this->entityManager
                ->getRepository(StatementFile::class)
                ->searchByCriterionAdmin($request);
        }
        return  $this->entityManager
            ->getRepository(StatementFile::class)
            ->searchAllMovements();
    }

}