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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zentlix\MainBundle\Application\Command\Site\Command;
use Zentlix\MainBundle\Domain\Site\Entity\Site;
use Zentlix\MainBundle\Domain\Site\Event\BeforeUpdate;
use Zentlix\MainBundle\Domain\Site\Event\BeforeDelete;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandBus;
use Zentlix\PageBundle\Application\Command\Page\DeleteCommand;
use Zentlix\PageBundle\Application\Command\Page\UpdateCommand;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;

class SiteSubscriber implements EventSubscriberInterface
{
    private CommandBus $commandBus;
    private PageRepository $pageRepository;
    private string $defaultTemplate;

    public function __construct(CommandBus $commandBus, PageRepository $pageRepository, string $template)
    {
        $this->commandBus = $commandBus;
        $this->pageRepository = $pageRepository;
        $this->defaultTemplate = $template;
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

        if($site->getTemplate()->getId()->equals($command->template->getId())) {
            $pages = $this->pageRepository->findBySite($site->getId());

            array_walk($pages, function($page) {
                $command = new UpdateCommand($page);
                $command->template = $this->defaultTemplate;
                $this->commandBus->handle($command);
            });
        }
    }

    public function onBeforeDelete(BeforeDelete $beforeDelete): void
    {
        $siteId = $beforeDelete->getCommand()->site->getId();

        $pages = $this->pageRepository->findBySite($siteId);
        array_walk($pages, fn($page) => $this->commandBus->handle(new DeleteCommand($page)));
    }
}