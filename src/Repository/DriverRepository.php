<?php

namespace App\Repository;

use App\Entity\Driver;
use App\Model\DriverReportFiltersModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Driver>
 *
 * @method Driver|null find($id, $lockMode = null, $lockVersion = null)
 * @method Driver|null findOneBy(array $criteria, array $orderBy = null)
 * @method Driver[]    findAll()
 * @method Driver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DriverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Driver::class);
    }

    public function save(Driver $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Driver $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByPlate(string $plate)
    {
        return $this->createQueryBuilder('d')
            ->join('d.vehicle', 'v')
            ->andWhere('v.plate = :plate')->setParameter('plate', $plate)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param DriverReportFiltersModel $driverReportFiltersModel
     * @return QueryBuilder
     */
    public function findAllQueryBuilder(DriverReportFiltersModel $driverReportFiltersModel): QueryBuilder
    {
        $qb = $this->createQueryBuilder('d');
        if ($driverReportFiltersModel->name) {
            $qb
                ->andWhere('d.name = :name')
                ->setParameter('name', $driverReportFiltersModel->name);
        }

        if ($driverReportFiltersModel->document) {
            $qb
                ->andWhere('d.document = :document')
                ->setParameter('document', $driverReportFiltersModel->document);
        }

        if ($driverReportFiltersModel->plate) {
            $qb
                ->join('d.vehicle', 'v')
                ->andWhere('v.plate = :plate')
                ->setParameter('plate', $driverReportFiltersModel->plate);
        }


        if ($driverReportFiltersModel->sort_by) {
            $qb->orderBy("d.$driverReportFiltersModel->sort_by", $driverReportFiltersModel->sort_order);
        }

        return $qb;
    }
}
