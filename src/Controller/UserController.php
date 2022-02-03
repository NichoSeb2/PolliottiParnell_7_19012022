<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Society;
use OpenApi\Attributes\Tag;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
use App\Service\ValidatorErrorFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Tag(name: "Users")]
#[Security(name: "Bearer")]
class UserController extends AbstractController {
    private const USER_PER_PAGE = 5;

    #[Route('/api/users', name: 'app_users_get', methods: ['GET'], format: 'json')]
    /**
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The current page",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns a paginated list of users",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"user"}))
     *     )
     * )
     */
    public function usersGet(Request $request, UserRepository $userRepository): Response {
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
    /**
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user id",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of an user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"user"}))
     *     )
     * )
     * @OA\Response(
     *     response=403,
     *     description="You are not allowed to view this user.",
     * )
     * @OA\Response(
     *     response=404,
     *     description="No user found for the provided id.",
     * )
     */
    public function userGet(?User $user): Response {
        if (is_null($user)) {
            throw new NotFoundHttpException("No user found for the provided id.");
        }

        /** @var Society $society */
        $society = $this->getUser();

        if ($user->getSociety()->getId() != $society->getId()) {
            return $this->json([
                'code' => "403", 
                'message' => "You are not allowed to view this user."
            ], 403);
        }

        return $this->json([
            'code' => 200, 
            'data' => $user
        ], 200, [], ['groups' => "user_detail"]);
    }

    #[Route('/api/users', name: 'app_user_post', methods: ['POST'], format: 'json')]
    public function userPost(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator): Response {
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
        ], 201, [], ['groups' => "user_detail"]);
    }

    #[Route('/api/users/{id}', name: 'app_user_delete', methods: ['DELETE'], format: 'json')]
    public function userDelete(?User $user, EntityManagerInterface $entityManager): Response {
        if (is_null($user)) {
            throw new NotFoundHttpException("No user found for the provided id.");
        }

        /** @var Society $society */
        $society = $this->getUser();

        if ($user->getSociety()->getId() != $society->getId()) {
            return $this->json([
                'code' => "403", 
                'message' => "You are not allowed to delete this user."
            ], 403);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json([
            'code' => 200, 
        ], 200);
    }
}
