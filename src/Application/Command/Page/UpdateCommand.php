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
use Zentlix\MainBundle\Infrastructure\Share\Bus\UpdateCommandInterface;
use Zentlix\PageBundle\Domain\Page\Entity\Page;

class UpdateCommand extends Command implements UpdateCommandInterface
{
    /** @Constraints\NotBlank() */
    public ?string $code;

    public function __construct(Page $page)
    {
        $this->entity = $page;

        $this->title    = $page->getTitle();
        $this->content  = $page->getContent();
        $this->active   = $page->isActive();
        $this->code     = $page->getCode();
        $this->template = $page->getTemplate();
        $this->sort     = $page->getSort();
        $this->site     = $page->getSite()->getId();
        $this->setMeta($page->getMetaTitle(), $page->getMetaDescription(), $page->getMetaKeywords());
    }
}