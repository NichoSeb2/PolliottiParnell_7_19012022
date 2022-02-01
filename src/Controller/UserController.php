<?php

namespace App\Controller;

use App\Entity\Society;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController {
    private const USER_PER_PAGE = 5;

    #[Route('/api/users', name: 'app_users', methods: ['GET'], format: 'json')]
    public function users(Request $request, UserRepository $userRepository): Response {
        /** @var Society $society */
        $society = $this->getUser();

        $page = (int) $request->query->get("page");
        $page = ($page > 0) ? $page : 1;

        return $this->json([
            'code' => 200, 
            'data' => $userRepository->search($society, "asc", $page, self::USER_PER_PAGE)
        ], 200, [], ['groups' => "user"]);
    }
}
