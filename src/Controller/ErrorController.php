<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ErrorController extends AbstractController{
    public function __construct(private KernelInterface $kernel) {}

    public function show(FlattenException $exception) {
        $result = [
            'code' => $exception->getStatusCode(), 
            'message' => $exception->getMessage()
        ];

        if (in_array($this->kernel->getEnvironment(), ['dev'])) {
            $result['debug'] = $exception;
        }

        return $this->json($result, $exception->getStatusCode());
    }
}
