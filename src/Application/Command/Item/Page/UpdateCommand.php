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

use Zentlix\MainBundle\Infrastructure\Share\Bus\UpdateCommandInterface;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;

class UpdateCommand extends Command implements UpdateCommandInterface
{
    public function __construct(Item $item)
    {
        $this->entity = $item;
        $this->menu = $item->getMenu();

        $this->title     = $item->getTitle();
        $this->sort      = $item->getSort();
        $this->parent    = $item->getParent()->getId();
        $this->blank     = $item->isTargetBlank();
        $this->entity_id = $item->getEntityId();
    }
}