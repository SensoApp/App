<?php


namespace App\Events;


use App\Controller\UserController;
use Symfony\Contracts\EventDispatcher\Event;

class StatementFileDownloadEvent extends Event
{
    public const NAME = 'statement.downloaded';

    protected $controller;

    public function __construct(UserController $userController)
    {
        $this->controller = $userController;
    }

    public function getController()
    {
        return $this->controller;
    }
}