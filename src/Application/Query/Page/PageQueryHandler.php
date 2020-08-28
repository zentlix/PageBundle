<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Application\Query\Page;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Zentlix\MainBundle\Application\Query\NotFoundException;
use Zentlix\MainBundle\Application\Query\QueryHandlerInterface;
use Zentlix\PageBundle\Domain\Page\Event\BeforeView;
use Zentlix\PageBundle\Domain\Page\Read\PageFetcher;
use Zentlix\PageBundle\Domain\Page\Read\PageView;

class PageQueryHandler implements QueryHandlerInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private EntityManagerInterface $entityManager;
    private PageFetcher $pageFetcher;

    public function __construct(EventDispatcherInterface $eventDispatcher, EntityManagerInterface $entityManager, PageFetcher $pageFetcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->pageFetcher = $pageFetcher;
    }

    public function __invoke(PageQuery $query): PageView
    {
        $page = $this->pageFetcher->findByCode($query->getCode(), $query->getSiteId());

        if(is_null($page)) {
            throw new NotFoundException('Page not found.');
        }

        $page = $this->map($page);

        $page->views++;
        $this->pageFetcher->view($page->views, $page->id);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new BeforeView($page));

        return $page;
    }

    private function map(array $page): PageView
    {
        $meta = json_decode($page['meta'], true);

        $pageView = new PageView();

        $pageView->id = (int) $page['id'];
        $pageView->title = (string) $page['title'];
        $pageView->active = (int) $page['active'] === 1;
        $pageView->code = (string) $page['code'];
        $pageView->site_id = (int) $page['site_id'];
        $pageView->sort = (int) $page['sort'];
        $pageView->template = (string) $page['template'];
        $pageView->content = $page['content'] ?? null;
        $pageView->views = (int) $page['views'];
        $pageView->meta_title = $meta['title'] ?? null;
        $pageView->meta_description = $meta['description'] ?? null;
        $pageView->meta_keywords = $meta['keywords'] ?? null;

        return $pageView;
    }
}