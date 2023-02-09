<?php

namespace App\Controller\Api;

use App\Entity\Driver;
use App\Exception\CustomUnprocessableEntityException;
use App\Form\DriverType;
use App\Form\DriverUpdateType;
use App\Form\DriverReportFiltersType;
use App\Model\DriverModel;
use App\Model\DriverReportFiltersModel;
use App\Model\Paginator\PaginatorViewModel;
use App\Repository\DriverRepository;
use App\service\DriverService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidatorException;

#[Route('/driver')]
class DriverController extends BaseController
{
    #[Route('', name: 'app_driver_index', methods: ['GET'])]
    public function index(Request $request, DriverRepository $driverRepository): Response
    {
        try {
            $driverReportFiltersModel = new DriverReportFiltersModel();
            $form = $this->createForm(DriverReportFiltersType::class, $driverReportFiltersModel);
            $this->validateForm($form, $request);

            $qb = $driverRepository->findAllQueryBuilder($driverReportFiltersModel);
            $pagination = $this->getPaginationItems($request, $qb,  ['groups' => 'show']);

            return $this->json(new PaginatorViewModel(paginator: $pagination));
        } catch (ValidatorException $e) {
            $this->throwApiProblemException([$e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    #[Route('/{driver}', name: 'app_driver_show', methods: ['GET'])]
    public function show(Driver $driver, DriverRepository $driverRepository): Response
    {
        return $this->json($driverRepository->find($driver), context: ['groups' => 'show']);
    }

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

    #[Route('/{driver}', name: 'app_driver_delete', methods: ['DELETE'])]
    public function delete(Driver $driver, DriverRepository $driverRepository): Response
    {
        $driverRepository->remove($driver, true);

        return $this->json([]);
    }
}
