<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Domain\Page\Read;

use Doctrine\DBAL\Connection;

class PageFetcher
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findByCode(string $code, int $siteId): ?array
    {
        $sql = $this->connection->createQueryBuilder()->select(['*'])
            ->from('zentlix_page_pages')
            ->where('code = :code')
            ->andWhere('site_id = :site')
            ->getSQL();

        $result = $this->connection->fetchAssociative($sql, [':site' => $siteId, ':code' => $code]);

        return is_array($result) ? $result : null;
    }

    public function view(int $views, int $id): void
    {
        $this->connection->createQueryBuilder()
            ->update('zentlix_page_pages')
            ->set('views', $views)
            ->where('id = :id')
            ->setParameter(':id', $id)
            ->execute();
    }
}