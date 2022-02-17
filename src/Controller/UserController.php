<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Society;
use App\Repository\UserRepository;
use App\Security\Voter\SocietyVoter;
use JMS\Serializer\SerializerInterface;
use App\Service\ValidatorErrorFormatter;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/api/users')]
class UserController extends AbstractController {
    private const USER_PER_PAGE = 5;

    public function __construct(private SerializerInterface $serializer) {}

    #[Route('', name: 'app_users_get', methods: ['GET'], format: 'json')]
    public function readAllUser(Request $request, UserRepository $userRepository): Response {
        /** @var Society $society */
        $society = $this->getUser();

        $page = (int) $request->query->get("page");
        $page = ($page > 0) ? $page : 1;

        $response = [
            'code' => 200,
            'data' => $userRepository->search($society, "asc", $page, self::USER_PER_PAGE)
        ];

        return new Response($this->serializer->serialize($response, "json", SerializationContext::create()->setGroups(["user"])), 200);
    }

    #[Route('/{id}', name: 'app_user_get', methods: ['GET'], format: 'json')]
    #[ParamConverter("user", converter: "EntityParamConverter")]
    public function readUser(User $user): Response {
        $this->denyAccessUnlessGranted(SocietyVoter::USER_OWNERSHIP, $user, "You are not allowed to view this user");

        $response = [
            'code' => 200,
            'data' => $user
        ];

        return new Response($this->serializer->serialize($response, "json", SerializationContext::create()->setGroups(["user"])), 200);
    }

    #[Route('', name: 'app_user_post', methods: ['POST'], format: 'json')]
    public function createUser(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator): Response {
        /** @var Society $society */
        $society = $this->getUser();

        /** @var User $user */
        $user = $serializer->deserialize($request->getContent(), User::class, "json");

        $user->setSociety($society);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json([
                'code' => 400, 
                'errors' => ValidatorErrorFormatter::format($errors)
            ], 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        $response = [
            'code' => 201,
            'data' => $user
        ];

        return new Response($this->serializer->serialize($response, "json", SerializationContext::create()->setGroups(["user"])), 201);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'], format: 'json')]
    #[ParamConverter("user", converter: "EntityParamConverter")]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): Response {
        $this->denyAccessUnlessGranted(SocietyVoter::USER_OWNERSHIP, $user, "You are not allowed to delete this user");

        $entityManager->remove($user);
        $entityManager->flush();

        return new Response("", 204);
    }
}
