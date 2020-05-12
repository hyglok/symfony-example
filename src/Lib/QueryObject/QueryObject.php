<?php
declare(strict_types=1);

namespace Lib\QueryObject;

use Doctrine\ORM\EntityManagerInterface;

interface QueryObject
{
    public function getData(EntityManagerInterface $manager);
}