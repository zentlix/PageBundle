<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Domain\Page\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Zentlix\MainBundle\Domain\Shared\Repository\GetTrait;
use Zentlix\MainBundle\Domain\Shared\Repository\MaxSortTrait;
use Zentlix\PageBundle\Domain\Page\Entity\Page;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page|null findOneByCode(string $code)
 * @method Page      get($id, $lockMode = null, $lockVersion = null)
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    use GetTrait, MaxSortTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['sort' => 'asc']);
    }

    public function findBySite(int $siteId): array
    {
        return $this->findBy(['site' => $siteId], ['sort' => 'asc']);
    }

    public function assoc(): array
    {
        return array_column(
            $this->createQueryBuilder('a')
                ->select('a.id', 'a.title')
                ->orderBy('a.sort')
                ->getQuery()
                ->execute(), 'id', 'title'
        );
    }

    public function view(int $views, int $id): void
    {
        $this->createQueryBuilder('page')
            ->update(Page::class, 'page')
            ->set('page.views', ':views')
            ->where('page.id = :id')
            ->setParameter(':id', $id)
            ->setParameter(':views', $views)
            ->getQuery()
            ->execute();
    }

    public function getPopular(int $limit = 10): array
    {
        return $this->createQueryBuilder('page')
            ->orderBy('page.views', 'desc')
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();
    }
}