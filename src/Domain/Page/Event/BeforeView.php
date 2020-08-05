<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Domain\Page\Event;

use Zentlix\PageBundle\Domain\Page\Read\PageView;

class BeforeView
{
    private PageView $page;

    public function __construct(PageView $page)
    {
        $this->page = $page;
    }

    public function getPage(): PageView
    {
        return $this->page;
    }
}