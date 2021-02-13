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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandHandlerInterface;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\MainBundle\Domain\Site\Specification\ExistSiteSpecification;
use Zentlix\MainBundle\Domain\Template\Specification\ExistFileSpecification;
use Zentlix\PageBundle\Domain\Page\Entity\Page;
use Zentlix\PageBundle\Domain\Page\Event\BeforeCreate;
use Zentlix\PageBundle\Domain\Page\Event\AfterCreate;
use Zentlix\PageBundle\Domain\Page\Specification\UniqueCodeSpecification;

class CreateHandler implements CommandHandlerInterface
{
    private UniqueCodeSpecification $uniqueCodeSpecification;
    private ExistSiteSpecification $existSiteSpecification;
    private ExistFileSpecification $existTemplateSpecification;
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;
    private SiteRepository $siteRepository;
    private string $defaultTemplate;

    public function __construct(EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                UniqueCodeSpecification $uniqueCodeSpecification,
                                ExistSiteSpecification $existSiteSpecification,
                                ExistFileSpecification $existTemplateSpecification,
                                SiteRepository $siteRepository,
                                string $template)
    {
        $this->uniqueCodeSpecification = $uniqueCodeSpecification;
        $this->existSiteSpecification = $existSiteSpecification;
        $this->existTemplateSpecification = $existTemplateSpecification;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->siteRepository = $siteRepository;
        $this->defaultTemplate = $template;
    }

    public function __invoke(CreateCommand $command): void
    {
        if($command->code) {
            $this->uniqueCodeSpecification->isUnique($command->code, $command->site);
        }
        $this->existSiteSpecification->isExist($command->site);
        if($command->template !== $this->defaultTemplate) {
            $this->existTemplateSpecification->isExist($this->siteRepository->get($command->site)->getTemplate()->getFolder() . '/' . $command->template);
        }
        $command->site = $this->siteRepository->get($command->site);

        $this->eventDispatcher->dispatch(new BeforeCreate($command));

        $page = new Page($command);

        $this->entityManager->persist($page);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new AfterCreate($page, $command));
    }
}