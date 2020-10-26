<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Domain\Page\Specification;

use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\NotFoundException;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;
use function is_null;

final class ExistPageSpecification
{
    private PageRepository $pageRepository;
    private TranslatorInterface $translator;

    public function __construct(PageRepository $pageRepository, TranslatorInterface $translator)
    {
        $this->pageRepository = $pageRepository;
        $this->translator = $translator;
    }

    public function isExist(int $pageId): void
    {
        if(is_null($this->pageRepository->find($pageId))) {
            throw new NotFoundException(\sprintf($this->translator->trans('zentlix_page.not_exist'), $pageId));
        }
    }

    public function __invoke(int $pageId): void
    {
        $this->isExist($pageId);
    }
}