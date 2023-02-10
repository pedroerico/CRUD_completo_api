<?php

namespace App\Controller\Api;

use App\Exception\CustomUnprocessableEntityException;
use App\Form\RegisterType;
use App\Model\RegisterModel;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations\JsonContent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

#[Route('/auth')]
class AuthController extends BaseController
{
    /**
     * @OA\Post(
     *     summary="Criar um novo usuário",
     *     @OA\RequestBody(
     *          required=true,
     *          description="Objeto para criação de um usuário",
     *          @OA\JsonContent(
     *            type="object",
     *            ref=@Model(type=RegisterModel::class)
     *         )
     *      ),
     *      @OA\Response(
     *         response=200,
     *         description="Usuário criado!"
     *     )
     * )
     * @OA\Tag(name="Login Check")
     * @Security(name=null)
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/register', name: 'app_auth_register',  methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $registerModel = new RegisterModel();
        $form = $this->createForm(RegisterType::class, $registerModel);
        $this->validateForm($form, $request);
        try {
            $user = $this->userService->createUser($registerModel);
        } catch (CustomUnprocessableEntityException $e) {
            $this->throwApiProblemException([$e->getMessage()], $e->getStatus());
        }

        return $this->json(data: $user, status: Response::HTTP_CREATED, context: ['groups' => 'show']);
    }
}
