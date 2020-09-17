<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Domain\Page\Entity;

use Doctrine\ORM\Mapping;
use Gedmo\Mapping\Annotation\Slug;
use Zentlix\MainBundle\Domain\Site\Entity\Site;
use Zentlix\MainBundle\Domain\Shared\Entity\Eventable;
use Zentlix\MainBundle\Domain\Shared\Entity\MetaTrait;
use Zentlix\MainBundle\Domain\Shared\Entity\SortTrait;
use Zentlix\PageBundle\Application\Command\Page\CreateCommand;
use Zentlix\PageBundle\Application\Command\Page\UpdateCommand;

/**
 * @Mapping\Entity(repositoryClass="Zentlix\PageBundle\Domain\Page\Repository\PageRepository")
 * @Mapping\Table(name="zentlix_page_pages", uniqueConstraints={
 *     @Mapping\UniqueConstraint(columns={"code"})
 * })
 */
class Page implements Eventable
{
    use MetaTrait, SortTrait;

    /**
     * @Mapping\Id()
     * @Mapping\GeneratedValue()
     * @Mapping\Column(type="integer")
     */
    private $id;

    /** @Mapping\Column(type="string", length=255) */
    private $title;

    /** @Mapping\Column(type="text", nullable=true) */
    private $content;

    /** @Mapping\Column(type="boolean", options={"default": "1"}) */
    private $active;

    /**
     * @Slug(fields={"title"}, updatable=false, unique=true, unique_base="site")
     * @Mapping\Column(type="string", length=64)
     */
    private $code;

    /** @Mapping\Column(type="integer", options={"default": "0"}) */
    private $views;

    /** @Mapping\Column(type="string", length=255) */
    private $template;

    /**
     * @var Site
     * @Mapping\ManyToOne(targetEntity="Zentlix\MainBundle\Domain\Site\Entity\Site", inversedBy="pages")
     * @Mapping\JoinColumn(name="site_id", referencedColumnName="id", nullable=true)
     */
    private $site;

    public function __construct(CreateCommand $command)
    {
        $this->views = 0;

        $this->setValuesFromCommands($command);
    }

    public function update(UpdateCommand $command)
    {
        $this->setValuesFromCommands($command);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function isCodeEqual(string $code): bool
    {
        return $code === $this->code;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param CreateCommand|UpdateCommand $command
     */
    private function setValuesFromCommands($command): void
    {
        $this->title = $command->title;
        $this->content = $command->content;
        $this->active = $command->active;
        $this->meta = $command->getMeta();
        $this->code = $command->code;
        $this->template = $command->template;
        $this->sort = $command->sort;
    }
}