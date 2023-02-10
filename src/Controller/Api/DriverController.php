<?php

namespace App\Controller\Api;

use App\Entity\Driver;
use App\Exception\CustomUnprocessableEntityException;
use App\Form\DriverReportFiltersType;
use App\Form\DriverType;
use App\Form\DriverUpdateType;
use App\Message\CoordinateMessage;
use App\Model\DriverModel;
use App\Model\DriverReportFiltersModel;
use App\Model\Paginator\PaginatorViewModel;
use App\Repository\DriverRepository;
use App\service\DriverService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @OA\Tag(name="Driver")
 * @Security(name="Bearer")
 */
#[Route('/driver')]
class DriverController extends BaseController
{
    /**
     * @OA\Get(
     *     summary="Mostra todos os resultados ou com filtro",
     *     @OA\Parameter(
     *         name="document",
     *         in="query",
     *         description="Filtra por documento do motorista",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Filtra por nome do motorista",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="plate",
     *         in="query",
     *         description="Filtra pela placa do veículo",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/limit"),
     *     @OA\Parameter(ref="#/components/parameters/sortBy"),
     *     @OA\Parameter(ref="#/components/parameters/sortOrder"),
     *     @OA\Response(
     *         response=200,
     *         description="Retorna todos os resultados do motorista",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                  property="items",
     *                  type="array",
     *                  @OA\Items(ref=@Model(type=Driver::class, groups={"show"}))
     *             ),
     *             @OA\Property(property="total", type="integer", example=1),
     *             @OA\Property(property="page", type="integer", example=1),
     *             @OA\Property(property="limit", type="integer", example=10),
     *         )
     *     )
     * )
     */
    #[Route('', name: 'app_driver_index', methods: ['GET'])]
    public function index(Request $request, DriverRepository $driverRepository): Response
    {
        try {
            $driverReportFiltersModel = new DriverReportFiltersModel();
            $form = $this->createForm(DriverReportFiltersType::class, $driverReportFiltersModel);
            $this->validateForm($form, $request);

            $qb = $driverRepository->findAllQueryBuilder($driverReportFiltersModel);
            $pagination = $this->getPaginationItems($request, $qb, ['groups' => 'show']);

            return $this->json(new PaginatorViewModel(paginator: $pagination));
        } catch (ValidatorException $e) {
            $this->throwApiProblemException([$e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @OA\Get(
     *     summary="Mostra o resultado do motorista pelo ID",
     *     @OA\Parameter(ref="#/components/parameters/driver"),
     *     @OA\Response(
     *          response=200,
     *          description="Retorna dados de um motorista",
     *          @OA\JsonContent(ref=@Model(type=Driver::class, groups={"show"}))
     *     )
     * )
     */
    #[Route('/{driver}', name: 'app_driver_show', methods: ['GET'])]
    public function show(Driver $driver, DriverRepository $driverRepository): Response
    {
        return $this->json($driverRepository->find($driver), context: ['groups' => 'show']);
    }

    /**
     * @OA\Put(
     *     summary="Atualiza por completo os dados do motorista",
     *     description="Necessário enviar todo o request para atualizar",
     *     @OA\Parameter(ref="#/components/parameters/driver"),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref=@Model(type=DriverModel::class))),
     *     @OA\Response(
     *         response=200,
     *         description="Motorista atualizado!",
     *         @OA\JsonContent(ref=@Model(type=Driver::class, groups={"show"}))
     *     ),
     * )
     * @OA\Patch(
     *     summary="Atualiza pacial os dados do motorista",
     *     description="Não é necessário enviar todos os dados para atualização, pode enviar apenas o que deseja atualizar",
     *     @OA\Parameter(ref="#/components/parameters/driver"),
     *     @OA\RequestBody(@OA\JsonContent(ref=@Model(type=DriverModel::class))),
     *     @OA\Response(
     *         response=200,
     *         description="Motorista atualizado!",
     *         @OA\JsonContent(ref=@Model(type=Driver::class, groups={"show"}))
     *     ),
     * )
     */
    #[Route('/{driver}', name: 'app_driver_update', methods: ['PUT', 'PATCH'])]
    public function update(Driver $driver, Request $request, DriverService $driverService): Response
    {
        $driverModel = new DriverModel($driver);
        $form = $this->createForm(DriverUpdateType::class, $driverModel);
        $this->validateForm($form, $request);
        try {
            return $this->json(data: $driverService->update($driver, $driverModel), context: ['groups' => 'show']);
        } catch (CustomUnprocessableEntityException $e) {
            $this->throwApiProblemException([$e->getMessage()], $e->getStatus());
        }
    }

    /**
     * @OA\Post(
     *     summary="Cria um motorista",
     *     @OA\Parameter(ref="#/components/parameters/driver"),
     *     @OA\Response(
     *         response=200,
     *         description="Motorista criado!",
     *         @OA\JsonContent(ref=@Model(type=Driver::class, groups={"show"}))
     *     ),
     * )
     */
    #[Route('', name: 'app_driver_create', methods: ['POST'])]
    public function create(Request $request, DriverService $driverService): Response
    {
        try {
            $driverModel = new DriverModel();
            $form = $this->createForm(DriverType::class, $driverModel);
            $this->validateForm($form, $request);

            return $this->json(data: $driverService->create($driverModel), context: ['groups' => 'show']);
        } catch (ValidatorException $e) {
            $this->throwApiProblemException([$e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (CustomUnprocessableEntityException $e) {
            $this->throwApiProblemException([$e->getMessage()], $e->getStatus());
        }
    }

    /**
     * @OA\Delete(
     *     summary="Deleta um motorista",
     *     @OA\Parameter(ref="#/components/parameters/driver"),
     *     @OA\Response(response=200, description="Motorista deletado!"),
     * )
     */
    #[Route('/{driver}', name: 'app_driver_delete', methods: ['DELETE'])]
    public function delete(Driver $driver, DriverRepository $driverRepository): Response
    {
        $driverRepository->remove($driver, true);

        return $this->json([]);
    }

    /**
     * @OA\Post(
     *     summary="Recebe dados de uma coordenada",
     *     @OA\Parameter(ref="#/components/parameters/driver"),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="latitude", type="integer", example=65.4564),
     *              @OA\Property(property="longitude", type="integer", example=-65.4564),
     *          )
     *     ),
     *     @OA\Response(response=200, description="Coordenadas recebidas!"),
     * )
     */
    #[Route('/{driver}/coordinates', name: 'app_driver_coordinates', methods: ['POST'])]
    public function coordinates(Driver $driver, Request $request, MessageBusInterface $bus): Response
    {
        $message = new CoordinateMessage($driver, $request->toArray());
        $bus->dispatch($message);

        return $this->json([]);
    }
}
