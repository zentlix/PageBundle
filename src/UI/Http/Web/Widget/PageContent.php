<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\UI\Http\Web\Widget;

use Twig\Extension\AbstractExtension;
use Twig\Environment;
use Twig\TwigFunction;
use Zentlix\MainBundle\Domain\VisualEditor\Service\VisualEditor;
use Zentlix\PageBundle\Domain\Page\Read\PageView;
use Zentlix\PageBundle\Domain\Page\Entity\Page;
use Zentlix\PageBundle\UI\Http\Web\Form\Page\VisualEditForm;

class PageContent extends AbstractExtension
{
    private VisualEditor $visualEditor;

    public function __construct(VisualEditor $visualEditor)
    {
        $this->visualEditor = $visualEditor;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pageContentWidget', [$this, 'getPageContent'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function getPageContent(Environment $twig, PageView $page): ?string
    {
        if($this->visualEditor->isEnabled() === false) {
            return $page->content;
        }

        return $twig->render('@PageBundle/widgets/page_content.html.twig', [
            'page'  => $page,
            'class' => Page::class,
            'form'  => VisualEditForm::class
        ]);
    }
}