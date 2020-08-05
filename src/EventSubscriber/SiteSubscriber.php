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
use Zentlix\MainBundle\Application\Command\Site\Command;
use Zentlix\MainBundle\Domain\Site\Entity\Site;
use Zentlix\MainBundle\Domain\Site\Event\Site\BeforeUpdate;
use Zentlix\MainBundle\Domain\Site\Event\Site\BeforeDelete;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;

class SiteSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private PageRepository $pageRepository;
    private string $template;

    public function __construct(EntityManagerInterface $entityManager, PageRepository $pageRepository, string $template)
    {
        $this->entityManager = $entityManager;
        $this->pageRepository = $pageRepository;
        $this->template = $template;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeUpdate::class  => 'onBeforeUpdate',
            BeforeDelete::class => 'onBeforeDelete'
        ];
    }

    public function onBeforeUpdate(BeforeUpdate $beforeUpdate): void
    {
        /** @var Command $command */
        $command = $beforeUpdate->getCommand();
        /** @var Site $site */
        $site = $command->getEntity();

        if($site->getTemplate()->getId() !== $command->template->getId()) {
            $pages = $this->pageRepository->findBySite($site->getId());

            foreach ($pages as $page) {
                $page->setTemplate($this->template);
            }

            $this->entityManager->flush();
        }
    }

    public function onBeforeDelete(BeforeDelete $beforeDelete): void
    {
        $siteId = $beforeDelete->getCommand()->site->getId();

        $pages = $this->pageRepository->findBySite($siteId);
        foreach ($pages as $page) {
            $page->setTemplate($this->template);
            $page->setSite(null);
        }

        $this->entityManager->flush();
    }
}