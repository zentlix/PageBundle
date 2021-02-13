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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
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

    public function index(): Response
    {
        return $this->listResource(new DataTableQuery(Table::class),'@PageBundle/admin/pages/pages.html.twig');
    }

    public function create(): Response
    {
        return $this->createResource(new CreateCommand(), CreateForm::class, '@PageBundle/admin/pages/create.html.twig');
    }

    public function update(Page $page): Response
    {
        return $this->updateResource(
            new UpdateCommand($page), UpdateForm::class, '@PageBundle/admin/pages/update.html.twig', ['page' => $page]
        );
    }

    public function changeActivity(Page $page, Request $request): Response
    {
        $command = new UpdateCommand($page);
        $command->active = (bool) $request->get('active');

        try {
            $this->exec($command);
            return $this->json(['success' => true, 'message' => static::$updateSuccessMessage]);
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage());
        }
    }

    public function delete(Page $page): Response
    {
        return $this->deleteResource(new DeleteCommand($page));
    }

    public function getTemplates(Request $request, SiteRepository $siteRepository, TranslatorInterface $translator): Response
    {
        $defaultTemplate = $this->container->getParameter('zentlix_page.page_template');

        $templates = $siteRepository->get((int) $request->get('site'))
            ->getTemplate()
            ->getConfigParam('pages', ['zentlix_main.default_template' => $defaultTemplate]);

        $result = [];
        foreach ($templates as $title => $template) {
            $result[$translator->trans($title)] = $template;
        }

        return $this->json($result);
    }
}