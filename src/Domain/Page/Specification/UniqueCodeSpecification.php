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
use Zentlix\MainBundle\Domain\Shared\Specification\AbstractSpecification;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;

final class UniqueCodeSpecification extends AbstractSpecification
{
    private PageRepository $pageRepository;
    private TranslatorInterface $translator;

    public function __construct(PageRepository $pageRepository, TranslatorInterface $translator)
    {
        $this->pageRepository = $pageRepository;
        $this->translator = $translator;
    }

    public function isUnique(array $param): bool
    {
        return $this->isSatisfiedBy($param);
    }

    public function isSatisfiedBy($value): bool
    {
        if($this->pageRepository->findOneBy(['code' => $value['code'], 'site' => $value['site']]) !== null) {
            throw new NonUniqueResultException(sprintf($this->translator->trans('zentlix_page.already_exist'), $value['code']));
        }

        return true;
    }

    public function __invoke(array $param)
    {
        return $this->isUnique($param);
    }
}