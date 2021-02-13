<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\UI\Http\Web\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Zentlix\MainBundle\UI\Http\Web\Controller\Admin\AbstractAdminController;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\PageBundle\Application\Command\Item\Page\CreateCommand;
use Zentlix\PageBundle\Application\Command\Item\Page\DeleteCommand;
use Zentlix\PageBundle\Application\Command\Item\Page\UpdateCommand;
use Zentlix\PageBundle\UI\Http\Web\Form\Item\Page\CreateForm;
use Zentlix\PageBundle\UI\Http\Web\Form\Item\Page\UpdateForm;

class MenuPageController extends AbstractAdminController
{
    public function create(Menu $menu, Request $request): Response
    {
        try {
            $command = new CreateCommand($menu);
            $form = $this->createForm(CreateForm::class, $command);
            $form->handleRequest($request);
            $this->exec($command);
            $this->addFlash('success', 'zentlix_menu.item.create.success');

            return $this->redirectToRoute('admin.menu.update', ['id' => $menu->getId(), 'items' => 1]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('admin.menu.update', ['id' => $menu->getId(), 'items' => 1]);
        }
    }

    public function update(Item $item, Request $request): Response
    {
        try {
            $command = new UpdateCommand($item);
            $form = $this->createForm(UpdateForm::class, $command);
            $form->handleRequest($request);
            $this->exec($command);
            $this->addFlash('success', 'zentlix_menu.item.update.success');

            return $this->redirectToRoute('admin.menu.update', ['id' => $item->getMenu()->getId(), 'items' => 1]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('admin.menu.update', ['id' => $item->getMenu()->getId(), 'items' => 1]);
        }
    }

    public function delete(Item $item): Response
    {
        try {
            $menuId = $item->getMenu()->getId();
            $command = new DeleteCommand($item);
            $this->exec($command);
            $this->addFlash('success', 'zentlix_menu.item.delete.success');

            return $this->redirectToRoute('admin.menu.update', ['id' => $menuId, 'items' => 1]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('admin.menu.update', ['id' => $menuId, 'items' => 1]);
        }
    }
}