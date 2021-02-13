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
    public function __construct(array $page)
    {
        $meta = json_decode($page['meta'], true);

        $this->id = (int) $page['id'];
        $this->title = (string) $page['title'];
        $this->active = (int) $page['active'] === 1;
        $this->code = (string) $page['code'];
        $this->site_id = (string) $page['site_id'];
        $this->sort = (int) $page['sort'];
        $this->template = (string) $page['template'];
        $this->content = $page['content'] ?? null;
        $this->views = (int) $page['views'];
        $this->meta_title = $meta['title'] ?? null;
        $this->meta_description = $meta['description'] ?? null;
        $this->meta_keywords = $meta['keywords'] ?? null;
    }

    public int $id;
    public string $title;
    public bool $active;
    public string $code;
    public int $views;
    public string $site_id;
    public int $sort;
    public ?string $template;
    public ?string $content;
    public ?string $meta_title;
    public ?string $meta_description;
    public ?string $meta_keywords;
}