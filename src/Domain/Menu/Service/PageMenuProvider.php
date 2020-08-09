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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zentlix\MenuBundle\Domain\Menu\Service\ProviderInterface;
use Zentlix\MenuBundle\Domain\Menu\Service\MenuEntityProviderInterface;
use Zentlix\PageBundle\Domain\Page\Entity\Page;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;

class PageMenuProvider implements ProviderInterface, MenuEntityProviderInterface
{
    private PageRepository $pageRepository;
    private UrlGeneratorInterface $router;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $router)
    {
        $this->pageRepository = $entityManager->getRepository(Page::class);
        $this->router = $router;
    }

    public function getTitle(): string
    {
        return 'zentlix_page.page';
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

    public function getUrl(array $item): string
    {
        $page = $this->pageRepository->get($item['entity_id']);

        $site = $page->getSite();
        if(is_null($site)) {
            return '';
        }

        return $this->router->generate(sprintf('page.show_%s', $site->getId()), ['code' => $page->getCode()]);
    }
}