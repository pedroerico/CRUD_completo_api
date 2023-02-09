<?php

namespace App\Controller\Api;

use App\Exception\CustomUnprocessableEntityException;
use App\Form\RegisterType;
use App\Model\RegisterModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth')]
class AuthController extends BaseController
{
    /**
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
