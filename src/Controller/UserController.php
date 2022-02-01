<?php

namespace App\Controller;

use App\Entity\User;
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

    #[Route('/api/users/{id}', name: 'app_user', methods: ['GET'], format: 'json')]
    public function user(User $user, Request $request): Response {
        /** @var Society $society */
        $society = $this->getUser();

        if ($user->getSociety()->getId() != $society->getId()) {
            return $this->json([
                'code' => "403", 
                'message' => "You are not allowed to view this user"
            ], 403);
        }

        return $this->json([
            'code' => 200, 
            'data' => $user
        ], 200, [], ['groups' => "user_detail"]);
    }
}
