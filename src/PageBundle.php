<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Zentlix\MainBundle\Domain\Route\Entity\Route;
use Zentlix\MainBundle\ZentlixBundleInterface;
use Zentlix\MainBundle\ZentlixBundleTrait;
use Zentlix\PageBundle\Application\Command;
use Zentlix\PageBundle\Application\Query;

class PageBundle extends Bundle implements ZentlixBundleInterface
{
    use ZentlixBundleTrait;

    public function getTitle(): string
    {
        return 'zentlix_page.pages';
    }

    public function getVersion(): string
    {
        return '0.1.1';
    }

    public function getDeveloper(): array
    {
        return ['name' => 'Zentlix', 'url' => 'https://zentlix.io'];
    }

    public function getDescription(): string
    {
        return 'zentlix_page.bundle_description';
    }

    public function configureRights(): array
    {
        return [
            Query\Page\DataTableQuery::class  => 'zentlix_page.view_pages',
            Command\Page\CreateCommand::class => 'zentlix_page.create.process',
            Command\Page\UpdateCommand::class => 'zentlix_page.update.process',
            Command\Page\DeleteCommand::class => 'zentlix_page.delete.process',
        ];
    }

    public function installFrontendRoutes(): array
    {
        return [
            new Route('page/{code}', 'zentlix_page.controller.page_controller', 'show', 'zentlix_page.page_view', 'page.show')
        ];
    }
}
