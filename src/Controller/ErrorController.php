<?php

namespace App\Controller;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ErrorController extends AbstractController{
    public function show(FlattenException $exception) {
        return $this->json([
            'message' => $exception->getMessage(), 
            'status' => $exception->getStatusCode()
        ], $exception->getStatusCode());
    }
}
