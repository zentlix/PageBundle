<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Domain\Menu\Service;

use Doctrine\ORM\EntityManagerInterface;
use Zentlix\MenuBundle\Domain\Menu\Service\ProviderInterface;
use Zentlix\MenuBundle\Domain\Menu\Service\MenuEntityProviderInterface;
use Zentlix\PageBundle\Domain\Page\Entity\Page;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;

class PageMenuProvider implements ProviderInterface, MenuEntityProviderInterface
{
    private PageRepository $pageRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->pageRepository = $entityManager->getRepository(Page::class);
    }

    public function getTitle(): string
    {
        return 'page.page';
    }

    public function getType(): string
    {
        return 'page';
    }

    public function isNeedUrl(): bool
    {
        return false;
    }

    public function getEntityClassName(): string
    {
        return Page::class;
    }

    public function getEntities(): array
    {
        return $this->pageRepository->assoc();
    }

    public function getEntityTitle(int $entityId): string
    {
        $page = $this->pageRepository->get($entityId);

        return $page->getTitle();
    }
}