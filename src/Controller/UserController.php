<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Society;
use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Schema;
use App\Repository\UserRepository;
use App\Security\Voter\SocietyVoter;
use App\Service\ValidatorErrorFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes\Response as APIResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes\Parameter as APIParameter;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Tag(name: "Users")]
#[Security(name: "Bearer")]
class UserController extends AbstractController {
    private const USER_PER_PAGE = 5;

    #[Route('/api/users', name: 'app_users_get', methods: ['GET'], format: 'json')]
    #[APIParameter(
        name: "page", 
        description: "The current page", 
        in: "query", 
        required: false, 
        schema: new Schema(null, null, null, null, null, null, "integer")
    )]
    #[APIResponse(
        response: 200, 
        description: "Returns a paginated list of users", 
    )]
    #[APIResponse(
        response: 401, 
        description: "JWT Token not found", 
    )]
    public function readAllUser(Request $request, UserRepository $userRepository): Response {
        /** @var Society $society */
        $society = $this->getUser();

        $page = (int) $request->query->get("page");
        $page = ($page > 0) ? $page : 1;

        return $this->json([
            'code' => 200, 
            'data' => $userRepository->search($society, "asc", $page, self::USER_PER_PAGE)
        ], 200, [], ['groups' => "user"]);
    }

    #[Route('/api/users/{id}', name: 'app_user_get', methods: ['GET'], format: 'json')]
    #[ParamConverter("user", converter: "EntityParamConverter")]
    #[APIParameter(
        name: "id", 
        description: "The user id", 
        in: "path", 
        required: true, 
        schema: new Schema(null, null, null, null, null, null, "integer")
    )]
    #[APIResponse(
        response: 200, 
        description: "Returns the details of an user", 
    )]
    #[APIResponse(
        response: 401, 
        description: "JWT Token not found", 
    )]
    #[APIResponse(
        response: 403, 
        description: "You are not allowed to view this user", 
    )]
    #[APIResponse(
        response: 404, 
        description: "No user found for the provided id", 
    )]
    public function readUser(User $user): Response {
        $this->denyAccessUnlessGranted(SocietyVoter::USER_OWNERSHIP, $user, "You are not allowed to view this user");

        return $this->json([
            'code' => 200, 
            'data' => $user
        ], 200, [], ['groups' => "user"]);
    }

    #[Route('/api/users', name: 'app_user_post', methods: ['POST'], format: 'json')]
    #[APIResponse(
        response: 201, 
        description: "User successfully created", 
    )]
    #[APIResponse(
        response: 401, 
        description: "JWT Token not found", 
    )]
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

        return $this->json([
            'code' => 201, 
            'data' => $user
        ], 201, [], ['groups' => "user"]);
    }

    #[Route('/api/users/{id}', name: 'app_user_delete', methods: ['DELETE'], format: 'json')]
    #[ParamConverter("user", converter: "EntityParamConverter")]
    #[APIParameter(
        name: "id", 
        description: "The user id", 
        in: "path", 
        required: true, 
        schema: new Schema(null, null, null, null, null, null, "integer")
    )]
    #[APIResponse(
        response: 204, 
        description: "User successfully deleted", 
    )]
    #[APIResponse(
        response: 401, 
        description: "JWT Token not found", 
    )]
    #[APIResponse(
        response: 403, 
        description: "You are not allowed to delete this user", 
    )]
    #[APIResponse(
        response: 404, 
        description: "No user found for the provided id", 
    )]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): Response {
        $this->denyAccessUnlessGranted(SocietyVoter::USER_OWNERSHIP, $user, "You are not allowed to delete this user");

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json([], 204);
    }
}
