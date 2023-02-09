<?php

namespace App\Controller\Api;

use App\Exception\Api\ApiProblem;
use App\Exception\Api\ApiProblemException;
use App\Model\Paginator\PaginatorModel;
use App\service\UserService;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseController extends AbstractController
{
    /**
     * @param ManagerRegistry $doctrine
     * @param TranslatorInterface $translator
     * @param SerializerInterface $serializer
     * @param UserService $userService
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        protected ManagerRegistry $doctrine,
        protected TranslatorInterface $translator,
        protected SerializerInterface $serializer,
        protected UserService $userService,
        protected PaginatorInterface $paginator,
    ) {
    }

    /**
     * Returns the body of a request in array format
     *
     * @param Request $request
     * @return mixed
     */
    protected function getParsedBody(Request $request): mixed
    {
        return json_decode($request->getContent(), true);
    }

    /**
     * Process the received data and submit a form
     *
     * @param Request $request
     * @param FormInterface $form
     * @throws ApiProblemException if the JSON received in the request is not valid
     */
    protected function processForm(Request $request, FormInterface $form): void
    {
        if ($request->getMethod() == 'GET') {
            $data = $request->query->all();
        } else {
            $data = $this->getParsedBody($request);
            if ($data === null) {
                $apiProblem = new ApiProblem(422, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);
                throw new ApiProblemException($apiProblem);
            }
        }

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    /**
     * Gathers errors found in a form
     *
     * @param FormInterface $form Invalid form to have errors gathered
     * @return array errors found in the form
     */
    protected function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    /**
     * Validates a form and throws an exception that stops the request if it is not valid
     *
     * @param FormInterface $form
     * @param Request $request
     */
    protected function validateForm(FormInterface $form, Request $request): void
    {
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }
    }

    /**
     * Prepare and execute the throw of a generic exception
     *
     * @param array $errors Errors found
     * @param integer $statusCode HTTP status code
     */
    protected function throwApiProblemException(array $errors, int $statusCode): void
    {
        $apiProblem = new ApiProblem($statusCode);
        $apiProblem->set('errors', $errors);
        throw new ApiProblemException($apiProblem);
    }

    /**
     * Prepares and executes throwing an appropriate exception for a validation issue encountered in a form
     *
     * @param FormInterface $form Invalid form
     * @throws ApiProblemException
     */
    protected function throwApiProblemValidationException(FormInterface $form): void
    {
        $errors = $this->getErrorsFromForm($form);
        $apiProblem = new ApiProblem(Response::HTTP_UNPROCESSABLE_ENTITY, ApiProblem::TYPE_VALIDATION_ERROR);
        $apiProblem->set(name: 'errors', value: $errors);
        throw new ApiProblemException($apiProblem);
    }

    /**
     * @param Request $request
     * @param QueryBuilder $queryBuilder
     * @param string|null $format
     * @param array|null $context
     * @return PaginationInterface
     */
    protected function getPaginationItems(
        Request $request,
        QueryBuilder $queryBuilder,
        ?array $context = [],
        ?string $format = 'json'
    ): PaginationInterface {
        $paginatorModel = new PaginatorModel();
        $form = $this->createForm($paginatorModel::FORM_TYPE, $paginatorModel);
        $this->validateForm($form, $request);

        $pagination = $this->paginator->paginate($queryBuilder, $paginatorModel->page, $paginatorModel->limit);
        $data = [];
        foreach ($pagination->getItems() as $item) {
            $data[] = json_decode($this->serializer->serialize($item, $format, $context));
        }
        $pagination->setItems($data);

        return $pagination;
    }
}
