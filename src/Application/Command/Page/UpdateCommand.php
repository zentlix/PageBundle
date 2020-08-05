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
use Symfony\Component\HttpFoundation\Request;
use Zentlix\MainBundle\Application\Command\UpdateCommandInterface;
use Zentlix\PageBundle\Domain\Page\Entity\Page;

class UpdateCommand extends Command implements UpdateCommandInterface
{
    /** @Constraints\NotBlank() */
    public ?string $code;

    public function __construct(Page $page, Request $request = null)
    {
        if($request) {
            $main = $request->request->get('main');
            $content = $request->request->get('content');
        }

        $page->getSite() ? $siteId = $page->getSite()->getId() : $siteId = null;

        $this->entity = $page;
        $this->title = $main['title'] ?? $page->getTitle();
        $this->content = $content['content'] ?? $page->getContent();
        $this->active = $main['active'] ?? $page->isActive();
        $this->code = $main['code'] ?? $page->getCode();
        $this->template = $main['template'] ?? $page->getTemplate();
        $this->sort = isset($main['sort']) ? (int) $main['sort'] : $page->getSort();
        $this->site = isset($main['site']) ? (int) $main['site'] : $siteId;
        $this->setMeta(
            $main['meta_title'] ?? $page->getMetaTitle(),
            $main['meta_description'] ?? $page->getMetaDescription(),
            $main['meta_keywords'] ?? $page->getMetaKeywords()
        );
    }
}