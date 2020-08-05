<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Domain\Page\Read;

class PageView
{
    public int $id;
    public string $title;
    public bool $active;
    public string $code;
    public int $views;
    public int $site_id;
    public int $sort;
    public ?string $template;
    public ?string $content;
    public ?string $meta_title;
    public ?string $meta_description;
    public ?string $meta_keywords;
}