<?php
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

class UploadHelper
{

    private $params;
    private $entityManager;
    private $security;

    const TIMESHEET_VALIDATED = 'Validated';

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $entityManager, Security $security)
    {
        $this->params = $params;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function uploadTimesheet($request) : bool
    {
        try{

            $filepath  = $this->params->get('kernel.project_dir').'/report/timesheet_signed';
            /**
             * @var UploadedFile $uploadedfile
             */
            $uploadedfile =  $request->files->get('file');

            $month = $request->request->get('month');

            if(!empty($month) && !is_null($uploadedfile)){

                $originalfilename = pathinfo($uploadedfile->getClientOriginalName(), PATHINFO_FILENAME);
                $newfilename = $originalfilename.uniqid().'.'.$uploadedfile->guessExtension();
                $finalpath = $filepath.'/'.$newfilename;

                $uploadedfile->move($filepath, $newfilename);

                $user = $this->security->getToken()->getUsername();

                $entity = $this->entityManager->getRepository(Timesheet::class);

                $id = $entity->selectPerMonth($user, $month);

                $entity->updateStatus(self::TIMESHEET_VALIDATED, $id, $finalpath);

                return true;

            } else {

                return false;
            }

        } catch (\Exception $exception){

            return $exception->getMessage();

        }
    }

}