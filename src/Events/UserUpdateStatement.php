<?php


namespace App\Events;

use App\Entity\StatementFile;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserUpdateStatement
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if(!$entity instanceof StatementFile){

            return;
        }

        $entityManager = $args->getObjectManager();

        $entityManager->getRepository(StatementFile::class)
                      ->updateEntityStatement();

    }

}