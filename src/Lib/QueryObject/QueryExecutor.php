<?php
declare(strict_types=1);

namespace Lib\QueryObject;

use Doctrine\ORM\EntityManagerInterface;

class QueryExecutor
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param QueryObject $queryObject
     *
     * @return mixed
     */
    public function execute(QueryObject $queryObject)
    {
        return $queryObject->getData($this->manager);
    }
}