<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\UI\Http\Web\Controller;

use Symfony\Component\HttpFoundation\Response;
use Zentlix\MainBundle\UI\Http\Web\Controller\AbstractController;
use Zentlix\MainBundle\Infrastructure\Share\Bus\NotFoundException;
use Zentlix\PageBundle\Application\Query\Page\PageQuery;

class PageController extends AbstractController
{
    public function show(string $code): Response
    {
        try {
            $page = $this->ask(new PageQuery($code, $this->site->getId()->toString()));
        } catch (NotFoundException $exception) {
            throw $this->createNotFoundException($this->translator->trans('zentlix_page.page_not_found'));
        }

        return $this->render($page->template, [
            'page'             => $page,
            'meta_title'       => $page->meta_title,
            'meta_description' => $page->meta_description,
            'meta_keywords'    => $page->meta_keywords
        ]);
    }
}