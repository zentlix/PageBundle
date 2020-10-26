<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zentlix\PageBundle\Domain\Page\Event\BeforeView;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;

class PageSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private PageRepository $pageRepository;

    public function __construct(EntityManagerInterface $entityManager, PageRepository $pageRepository)
    {
        $this->entityManager = $entityManager;
        $this->pageRepository = $pageRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeView::class => 'onBeforeView'
        ];
    }

    public function onBeforeView(BeforeView $beforeView): void
    {
        $page = $beforeView->getPage();

        $this->pageRepository->view($page->views + 1, $page->id);
        $this->entityManager->flush();
    }
}