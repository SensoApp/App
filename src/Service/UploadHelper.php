<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-08-06
 * Time: 08:10
 */

namespace App\Service;

use App\Entity\StatementFile;
use App\Entity\Timesheet;
use Cassandra\Exception\ExecutionException;
use DateTimeZone;
use Doctrine\DBAL\DBALException as DBALExceptionAlias;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use ParseCsv\Csv;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Validation;

class UploadHelper
{

    private $params;
    private $entityManager;
    private $security;
    private $validator;
    private $uploadfeedback = [];
    private $csvitems;
    private $dateGenerator;

    const TIMESHEET_VALIDATED = 'Validated';
    const NO_ERROR = 'no error';


    public function __construct(ParameterBagInterface $params, EntityManagerInterface $entityManager, Security $security, DateGeneratorService $dateGenerator)
    {
        $this->params = $params;
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->validator = Validation::createValidator();
        $this->dateGenerator = $dateGenerator;
    }

    public function uploadTimesheet($request): array
    {
        try {

            $filepath = $this->params->get('kernel.project_dir') . '/report/timesheet_signed';

            /**
             * @var UploadedFile $uploadedfile
             */
            $uploadedfile = $request->files->get('file');

            $month = $request->request->get('month');

            if (!empty($month) && !is_null($uploadedfile)) {

                $feedback = $this->validFileUpload($uploadedfile);

                if ($feedback !== self::NO_ERROR) {

                    $this->uploadfeedback = [
                        'status' => 'error',
                        'message' => $feedback
                    ];

                    return $this->uploadfeedback;

                } else {

                    $originalfilename = pathinfo($uploadedfile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newfilename = $originalfilename . uniqid() . '.' . $uploadedfile->guessExtension();
                    $finalpath = $filepath . '/' . $newfilename;

                    $uploadedfile->move($filepath, $newfilename);

                    $user = $this->security->getToken()->getUsername();

                    $entity = $this->entityManager->getRepository(Timesheet::class);

                    $id = $entity->selectPerMonth($user, $month);

                    $entity->updateStatus(self::TIMESHEET_VALIDATED, $id, $finalpath);

                    $this->uploadfeedback = [
                        'status' => 'success',
                        'message' => 'the invoice process has started',
                        'id' => $id
                    ];
                    return $this->uploadfeedback;
                }
            } else {

                $this->uploadfeedback = [
                    'status' => 'error',
                    'message' => 'Some data are expecting, null submitted'
                ];

                return $this->uploadfeedback;
            }
        } catch (\Exception $exception) {

            echo $exception->getMessage();

        }
    }

    public function uploadStatement($request)
    {
        try {

            $filepath = $this->params->get('kernel.project_dir') . '/report/statement';

            /**
             * @var UploadedFile $uploadedfile
             */
            $uploadedfile = $request->files->get('csv_file');

            $parsingFileName = preg_match('[_]', $uploadedfile->getClientOriginalName()) ? '_' : '-';

            $dataForAccount = explode($parsingFileName, $uploadedfile->getClientOriginalName());

            $account = $dataForAccount[1];

            if (!is_null($uploadedfile)) {

                $feedback = $this->validCsvFile($uploadedfile);

                if ($feedback !== self::NO_ERROR) {

                    $this->uploadfeedback = [
                        'status' => 'error',
                        'message' => $feedback
                    ];

                    return $this->uploadfeedback;

                } else {

                    $csv = new Csv();

                    $csv->auto($uploadedfile);

                    foreach ($csv->data as $first) {

                        $statement = new StatementFile();

                        foreach ($first as $title => $fileparser) {

                            try {

                                switch ($title) {

                                    case $title === 'Code' :
                                        $statement->setReferencemovement($fileparser);
                                        break;

                                    case $title === 'Text' :

                                        if ($fileparser === "FRAIS SUR OPERATION") {

                                            $statement->setOperations($fileparser);
                                            $statement->setReferencemovement($statement->getReferencemovement() . 'F');

                                        } else {

                                            $statement->setOperations($fileparser);
                                        }

                                        break;

                                    case $title === 'Communication' :
                                        $statement->setCommunication($fileparser);
                                        break;

                                    case $title === 'OperationDate' :
                                        $dateformatmin = explode('/', $fileparser);
                                        $day = $dateformatmin[0];
                                        $month = $dateformatmin[1];
                                        $year = $dateformatmin[2];
                                        $newarr = [$year, $month, $day];
                                        $newformat = implode('-', $newarr);
                                        $date = new \DateTime($newformat, new DateTimeZone('Europe/Luxembourg'));
                                        $statement->setOperationdate($date);
                                        break;

                                    case $title === 'Amount' :
                                        $amount = (float)$fileparser;
                                        $statement->setAmount($amount);
                                        break;

                                }
                                $statement->setAccount($account);
                                $this->entityManager->persist($statement);

                            } catch (\Exception $exception) {

                                echo $exception->getMessage();
                                die;
                            }
                        }
                    }

                    $this->entityManager->getRepository(StatementFile::class)
                        ->removeDuplicates($this->entityManager->getUnitOfWork()->getScheduledEntityInsertions());


                    $linesinstered = count($this->entityManager->getUnitOfWork()->getScheduledEntityInsertions());

                    $this->entityManager->flush();

                    $this->uploadfeedback = [
                        'status' => 'success',
                        'message' => 'the file is uploaded successfully, ' . $linesinstered . ' line(s) inserted',
                        'info' => $statement->getAccount(),
                        'insertedLines' => $linesinstered
                    ];

                    return $this->uploadfeedback;
                }

            } else {

                $this->uploadfeedback = [
                    'status' => 'error',
                    'message' => 'Some data are expecting, null submitted'
                ];

                return $this->uploadfeedback;
            }

        } catch (DBALExceptionAlias $exception) {

            if ($exception) {

                return $this->uploadfeedback = [
                    'status' => 'error',
                    'message' => $exception->getMessage()
                ];
            }
        }
    }

    public function validFileUpload($files): ?string
    {
        $violations = $this->validator->validate($files, [
            new Image([
                'maxSize' => '1M',
                'mimeTypes' => 'application/pdf'
            ])
        ]);

        if (count($violations) > 0) {

            foreach ($violations as $item) {

                $response = $item->getMessage();
            }

            return $response;
        }

        return self::NO_ERROR;
    }

    public function validCsvFile($file)
    {
        $violations = $this->validator->validate($file, [
            new File([

                'mimeTypes' => 'text/plain'
            ])
        ]);

        if (count($violations) > 0) {

            foreach ($violations as $item) {

                $response = $item->getMessage();
            }

            return $response;
        }

        return self::NO_ERROR;
    }

}