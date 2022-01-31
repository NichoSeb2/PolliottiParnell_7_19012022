<?php

use Pagerfanta\Pagerfanta;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractRepository extends ServiceEntityRepository {
    private function getMaxPage(int $nbResults, int $limit): int {
        return ceil($nbResults / $limit);
    }

    protected function paginate(QueryBuilder $qb, int $page = 1, int $limit = 10): Pagerfanta {
        if ($limit <= 0) {
            throw new \LogicException('$limit must be greater than 0.');
        }

        $pager = new Pagerfanta(new QueryAdapter($qb));

        if ($page > $this->getMaxPage($pager->getNbResults(), $limit)) {
            $page = $this->getMaxPage($pager->getNbResults(), $limit);
        }

        $pager->setAllowOutOfRangePages(true);
        $pager->setMaxPerPage($limit);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
