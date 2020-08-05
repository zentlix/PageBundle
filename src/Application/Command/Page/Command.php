<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Application\Command\Page;

use Symfony\Component\Validator\Constraints;
use Zentlix\MainBundle\Application\Command\DynamicPropertyCommand;
use Zentlix\MainBundle\Application\Command\MetaTrait;
use Zentlix\MainBundle\Application\Command\VisualEditorCommandInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandInterface;
use Zentlix\PageBundle\Domain\Page\Entity\Page;

class Command extends DynamicPropertyCommand implements CommandInterface, VisualEditorCommandInterface
{
    use MetaTrait;

    /** @Constraints\NotBlank() */
    public ?string $title = null;

    /** @Constraints\NotBlank() */
    public int $sort = 1;

    /** @Constraints\NotBlank() */
    public ?string $template = null;

    public ?string $content = null;
    public bool $active = true;
    public ?string $code = null;
    public ?int $site = null;
    protected ?Page $entity;

    public function getEntity(): Page
    {
        return $this->entity;
    }

    public function update(string $content = null): void
    {
        $this->content = $content;
    }

    public function getVisualEditedContent(): ?string
    {
        return $this->content;
    }
}