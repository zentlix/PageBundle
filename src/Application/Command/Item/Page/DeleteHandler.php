<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Application\Command\Item\Page;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandHandlerInterface;
use Zentlix\MenuBundle\Domain\Menu\Event\Item\BeforeDelete;
use Zentlix\MenuBundle\Domain\Menu\Event\Item\AfterDelete;
use Zentlix\MenuBundle\Domain\Menu\Service\Cache;

class DeleteHandler implements CommandHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(DeleteCommand $command): void
    {
        $itemId = $command->item->getId();

        $this->eventDispatcher->dispatch(new BeforeDelete($command));

        Cache::clearMenuTree($command->item->getMenu()->getCode());

        $this->entityManager->remove($command->item);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new AfterDelete($itemId));
    }
}