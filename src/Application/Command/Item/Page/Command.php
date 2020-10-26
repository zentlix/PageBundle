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

use Symfony\Component\Validator\Constraints;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandInterface;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\Infrastructure\Item\Bus\CreateCommandInterface;
use Zentlix\MenuBundle\Infrastructure\Item\Bus\UpdateCommandInterface;

class Command implements CommandInterface, CreateCommandInterface, UpdateCommandInterface
{
    /** @Constraints\NotBlank() */
    public ?string $title;
    /** @Constraints\NotBlank() */
    public ?string $provider;

    /** @var int|null|Item */
    public $parent;

    public int $sort = 1;
    public bool $blank = false;
    public ?int $entity_id = null;
    protected Menu $menu;
    protected Item $entity;

    public function getMenu(): Menu
    {
        return $this->menu;
    }

    public function getEntity(): Item
    {
        return $this->entity;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getEntityId()
    {
        return $this->entity_id;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function isBlank(): bool
    {
        return $this->blank;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    // for interface support.
    public function isCategory(): bool
    {
        return false;
    }

    public function getDepth(): int
    {
        return 0;
    }

    public function getUrl(): ?string
    {
        return null;
    }
}