<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\UI\Http\Web\DashboardWidget;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zentlix\MainBundle\Domain\Dashboard\Widgets\AbstractTableWidget;
use Zentlix\PageBundle\Domain\Page\Entity\Page;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;

class PopularPages extends AbstractTableWidget
{
    private UrlGeneratorInterface $router;
    private PageRepository $pageRepository;

    public function __construct(PageRepository $pageRepository, UrlGeneratorInterface $router)
    {
        $this->router = $router;
        $this->pageRepository = $pageRepository;
    }

    public function getTitle(): string
    {
        return 'zentlix_page.widgets.popular_pages';
    }

    public function getHeaders(): array
    {
        return ['zentlix_main.id', 'zentlix_page.page', 'zentlix_main.site.site', 'zentlix_main.symbol_code', 'zentlix_page.views'];
    }

    public function getRows(): array
    {
        /** @var Page[] $pages */
        $pages = $this->pageRepository->getPopular();

        $rows = [];
        foreach ($pages as $page) {
            $rows[] = [
                $page->getId(),
                sprintf('<a href="%s">%s</a>', $this->router->generate('admin.page.update', ['id' => $page->getId()]), $page->getTitle()),
                sprintf('<a href="%s">%s</a>', $this->router->generate('admin.site.update', ['id' => $page->getSite()->getId()]), $page->getSite()->getTitle()),
                $page->getCode(),
                $page->getViews()
            ];
        }

        return $rows;
    }
}