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
use Zentlix\MainBundle\UI\Http\Web\Controller\Admin\ResourceController;
use Zentlix\PageBundle\Application\Command\Page\CreateCommand;
use Zentlix\PageBundle\Application\Command\Page\DeleteCommand;
use Zentlix\PageBundle\Application\Command\Page\UpdateCommand;
use Zentlix\PageBundle\Application\Query\Page\DataTableQuery;
use Zentlix\PageBundle\Domain\Page\Entity\Page;
use Zentlix\PageBundle\UI\Http\Web\DataTable\Page\Table;
use Zentlix\PageBundle\UI\Http\Web\Form\Page\CreateForm;
use Zentlix\PageBundle\UI\Http\Web\Form\Page\UpdateForm;

class PageController extends ResourceController
{
    public static $createSuccessMessage = 'zentlix_page.create.success';
    public static $updateSuccessMessage = 'zentlix_page.update.success';
    public static $deleteSuccessMessage = 'zentlix_page.delete.success';
    public static $redirectAfterAction  = 'admin.page.list';

    public function index(Request $request): Response
    {
        return $this->listResource(new DataTableQuery(Table::class), $request);
    }

    public function create(Request $request): Response
    {
        return $this->createResource(new CreateCommand($request), CreateForm::class, $request);
    }

    public function update(Page $page, Request $request): Response
    {
        return $this->updateResource(new UpdateCommand($page, $request), UpdateForm::class, $request);
    }

    public function delete(Page $page): Response
    {
        return $this->deleteResource(new DeleteCommand($page));
    }
}