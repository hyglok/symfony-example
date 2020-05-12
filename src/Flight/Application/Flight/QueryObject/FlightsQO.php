<?php
declare(strict_types=1);

namespace Flight\Application\Flight\QueryObject;

use Doctrine\ORM\EntityManagerInterface;
use Flight\Model\Flight\Flight;
use Lib\QueryObject\QueryObject;

class FlightsQO implements QueryObject
{
    /**
     * @param EntityManagerInterface $manager
     *
     * @return Flight[]
     */
    public function getData(EntityManagerInterface $manager): array
    {
        return $manager->createQueryBuilder()
            ->select([
                'f.id as id',
                'f.status.status as status',
            ])
            ->from(Flight::class, 'f')
            ->getQuery()
            ->getResult();
    }
}