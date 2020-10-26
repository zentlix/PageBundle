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
use Zentlix\MainBundle\Domain\Site\Entity\Site;
use Zentlix\MainBundle\Infrastructure\Share\Bus\MetaTrait;
use Zentlix\MainBundle\Infrastructure\Share\Bus\VisualEditorCommandInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandInterface;
use Zentlix\PageBundle\Domain\Page\Entity\Page;

class Command implements CommandInterface, VisualEditorCommandInterface
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
    /**
     * @Constraints\NotBlank()
     * @var int|Site
     */
    public $site = null;
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