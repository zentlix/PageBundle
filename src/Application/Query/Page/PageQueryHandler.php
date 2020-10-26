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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\NotFoundException;
use Zentlix\MainBundle\Infrastructure\Share\Bus\QueryHandlerInterface;
use Zentlix\PageBundle\Domain\Page\Event\BeforeView;
use Zentlix\PageBundle\Domain\Page\Read\PageFetcher;
use Zentlix\PageBundle\Domain\Page\Read\PageView;
use function is_null;

class PageQueryHandler implements QueryHandlerInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private PageFetcher $pageFetcher;

    public function __construct(EventDispatcherInterface $eventDispatcher, PageFetcher $pageFetcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->pageFetcher = $pageFetcher;
    }

    public function __invoke(PageQuery $query): PageView
    {
        $page = $this->pageFetcher->findByCode($query->getCode(), $query->getSiteId());

        if(is_null($page)) {
            throw new NotFoundException('Page not found.');
        }

        $page = new PageView($page);

        $this->eventDispatcher->dispatch(new BeforeView($page));

        return $page;
    }
}