<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-08-06
 * Time: 08:10
 */

namespace App\Service;

use App\Entity\Timesheet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Validation;

class UploadHelper
{

    private $params;
    private $entityManager;
    private $security;
    private $validator;
    private $uploadfeedback = [];

    const TIMESHEET_VALIDATED = 'Validated';
    const NO_ERROR = 'no error';

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $entityManager, Security $security)
    {
        $this->params = $params;
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->validator = Validation::createValidator();
    }

    public function uploadTimesheet($request) : array
    {
        try{

            $filepath  = $this->params->get('kernel.project_dir').'/report/timesheet_signed';
            /**
             * @var UploadedFile $uploadedfile
             */
            $uploadedfile =  $request->files->get('file');

            $month = $request->request->get('month');

            if(!empty($month) && !is_null($uploadedfile)){

                $feedback = $this->validFileUpload($uploadedfile);

                if($feedback !== self::NO_ERROR){

                    $this->uploadfeedback = [
                                                'status'=>'error',
                                                'message' => $feedback
                                            ];

                    return $this->uploadfeedback;

                } else {

                    $originalfilename = pathinfo($uploadedfile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newfilename = $originalfilename.uniqid().'.'.$uploadedfile->guessExtension();
                    $finalpath = $filepath.'/'.$newfilename;

                    $uploadedfile->move($filepath, $newfilename);

                    $user = $this->security->getToken()->getUsername();

                    $entity = $this->entityManager->getRepository(Timesheet::class);

                    $id = $entity->selectPerMonth($user, $month);

                    $entity->updateStatus(self::TIMESHEET_VALIDATED, $id, $finalpath);

                    $this->uploadfeedback = [
                                                'status'=>'success',
                                                'message' => 'the invoice process has started',
                                                'id' => $id
                                            ];


                    return $this->uploadfeedback;

                }

            } else {

                $this->uploadfeedback = [
                                            'status'=>'error',
                                            'message' => 'Some data are expecting, null submitted'
                                        ];

                return $this->uploadfeedback;

            }


        } catch (\Exception $exception){

            echo $exception->getMessage();

        }
    }

    public function validFileUpload($files) :? string
    {
       $violations =  $this->validator->validate($files, [
            new Image([
                        'maxSize' => '1M',
                        'mimeTypes' => 'application/pdf'
                    ])
             ]);

       if(count($violations) > 0) {

           foreach ($violations as $item) {

               $response = $item->getMessage();
           }

           return $response;
       }

       return self::NO_ERROR ;
    }

}