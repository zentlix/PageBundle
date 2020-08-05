<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\UI\Http\Web\DashboardWidget;

use Zentlix\MainBundle\Domain\Dashboard\Widgets\Card\AbstractProgressbarWidget;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;

class PagesCount extends AbstractProgressbarWidget
{
    private PageRepository $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function getTitle(): string
    {
        return 'zentlix_page.widgets.count_pages';
    }

    public function getText(): string
    {
        return 'zentlix_page.widgets.pages';
    }

    public function getHelpText(): string
    {
        return 'zentlix_page.widgets.pages_help';
    }

    public function getProgressbarPercent(): float
    {
        return 100;
    }

    public function getValue(): int
    {
        return $this->pageRepository->count([]);
    }

    public function getBackgroundGradient(): string
    {
        return self::BACKGROUND_RED_GRADIENT;
    }

    public function getProgressbarBackgroundGradient(): string
    {
        return self::PROGRESSBAR_BACKGROUND_COLOR_WHITE;
    }

    public function getColor(): string
    {
        return '#fff';
    }

    public function getHelpTextColor(): string
    {
        return 'rgba(255,255,255,.6)';
    }
}