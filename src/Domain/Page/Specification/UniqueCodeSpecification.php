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

use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;
use function is_null;

final class UniqueCodeSpecification
{
    private PageRepository $pageRepository;
    private TranslatorInterface $translator;

    public function __construct(PageRepository $pageRepository, TranslatorInterface $translator)
    {
        $this->pageRepository = $pageRepository;
        $this->translator = $translator;
    }

    public function isUnique(string $code, $site): void
    {
        if(is_null($this->pageRepository->findOneBy(['code' => $code, 'site' => $site])) === false) {
            throw new NonUniqueResultException(sprintf($this->translator->trans('zentlix_page.already_exist'), $code));
        }
    }

    public function __invoke(string $code, int $site): void
    {
        $this->isUnique($code, $site);
    }
}