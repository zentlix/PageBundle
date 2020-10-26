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
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\Domain\Menu\Repository\ItemRepository;
use Zentlix\MenuBundle\Domain\Menu\Service\Cache;
use Zentlix\MenuBundle\Domain\Menu\Specification\ExistItemSpecification;
use Zentlix\MenuBundle\Domain\Menu\Event\Item\BeforeCreate;
use Zentlix\MenuBundle\Domain\Menu\Event\Item\AfterCreate;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;
use Zentlix\PageBundle\Domain\Page\Specification\ExistPageSpecification;

class CreateHandler implements CommandHandlerInterface
{
    private ExistItemSpecification $existItemSpecification;
    private ItemRepository $itemRepository;
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;
    private PageRepository $pageRepository;
    private ExistPageSpecification $existPageSpecification;

    public function __construct(EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                ExistItemSpecification $existItemSpecification,
                                ItemRepository $itemRepository,
                                PageRepository $pageRepository,
                                ExistPageSpecification $existPageSpecification)
    {
        $this->existItemSpecification = $existItemSpecification;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->itemRepository = $itemRepository;
        $this->pageRepository = $pageRepository;
        $this->existPageSpecification = $existPageSpecification;
    }

    public function __invoke(CreateCommand $command): void
    {
        $this->existItemSpecification->isExist((int) $command->parent);
        $command->parent = $this->itemRepository->get((int) $command->parent);
        $this->existPageSpecification->isExist($command->getEntityId());

        $command->title = $this->pageRepository->get($command->getEntityId())->getTitle();

        $this->eventDispatcher->dispatch(new BeforeCreate($command));

        $item = new Item($command);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        $this->itemRepository->reorder($item->getRoot(), 'sort');
        $this->entityManager->flush();

        Cache::clearMenuTree($item->getMenu()->getCode());

        $this->eventDispatcher->dispatch(new AfterCreate($item, $command));
    }
}