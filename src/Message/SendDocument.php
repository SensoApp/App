<?php


namespace App\Message;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class SendDocument
{

    private $request;
    private $security;


    public function __construct(Request $request, Security $security)
    {

        $this->request = $request;
        $this->security = $security;
    }

    public function getRequest() : Request
    {

        return $this->request;
    }

    public function getUsername() : Security
    {

        return $this->security;
    }

}