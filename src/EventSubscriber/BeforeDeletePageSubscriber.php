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
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandBus;
use Zentlix\PageBundle\Application\Command\Item\Page\DeleteCommand;
use Zentlix\PageBundle\Domain\Page\Event\BeforeDelete;
use Zentlix\MenuBundle\Domain\Menu\Repository\ItemRepository;

class BeforeDeletePageSubscriber implements EventSubscriberInterface
{
    private CommandBus $commandBus;
    private ItemRepository $itemRepository;

    public function __construct(CommandBus $commandBus, ItemRepository $itemRepository)
    {
        $this->commandBus = $commandBus;
        $this->itemRepository = $itemRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeDelete::class => 'onBeforeDelete'
        ];
    }

    public function onBeforeDelete(BeforeDelete $beforeDelete): void
    {
        $items = $this->itemRepository->findByEntityId($beforeDelete->getCommand()->page->getId());

        array_walk($items, fn($item) => $this->commandBus->handle(new DeleteCommand($item)));
    }
}