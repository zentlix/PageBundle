<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zentlix\MainBundle\Domain\AdminSidebar\Event\BeforeBuild;
use Zentlix\MainBundle\Domain\AdminSidebar\Service\MenuItemInterface;
use function is_null;

class SidebarSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeBuild::class => 'onBeforeBuild',
        ];
    }

    public function onBeforeBuild(BeforeBuild $beforeBuild): void
    {
        $sidebar = $beforeBuild->getSidebar();

        if(is_null($sidebar->findMenuItem('zentlix_main.content'))) {
            $sidebar->addSectionTitle('zentlix_main.content')->sort(150);
        }

        $sidebar
            ->addMenuItem('zentlix_page.pages')
            ->generateUrl('admin.page.list')
            ->icon(MenuItemInterface::ICON_DESCRIPTION)
            ->sort(190);
    }
}