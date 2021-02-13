<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Domain\Menu\Service;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Infrastructure\Menu\Service\ProviderInterface;
use Zentlix\PageBundle\Application\Command\Item\Page\CreateCommand;
use Zentlix\PageBundle\Application\Command\Item\Page\UpdateCommand;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;
use Zentlix\PageBundle\UI\Http\Web\Form\Item\Page\CreateForm;
use Zentlix\PageBundle\UI\Http\Web\Form\Item\Page\UpdateForm;
use function is_null;

class PageMenuProvider implements ProviderInterface
{
    private Environment $twig;
    private FormFactoryInterface $formFactory;
    private UrlGeneratorInterface $router;
    private PageRepository $pageRepository;

    public function __construct(Environment $twig,
                                FormFactoryInterface $formFactory,
                                UrlGeneratorInterface $router,
                                PageRepository $pageRepository)
    {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->pageRepository = $pageRepository;
        $this->router = $router;
    }

    public function getTitle(): string
    {
        return 'zentlix_page.page';
    }

    public static function getType(): string
    {
        return 'page';
    }

    public function getUrl(array $item): string
    {
        $page = $this->pageRepository->get($item['entity_id']);

        $site = $page->getSite();
        if(is_null($site)) {
            return '';
        }

        return $this->router->generate(sprintf('page.show_%s', $site->getId()->toString()), ['code' => $page->getCode()]);
    }

    public function getCreateForm(Menu $menu): string
    {
        return $this->twig->render('@PageBundle/admin/items/page/create.html.twig', [
            'form' => $this->formFactory->create(CreateForm::class, new CreateCommand($menu))->createView(),
            'menu' => $menu
        ]);
    }

    public function getUpdateForm(Item $item): string
    {
        return $this->twig->render('@PageBundle/admin/items/page/update.html.twig', [
            'form' => $this->formFactory->create(UpdateForm::class, new UpdateCommand($item))->createView(),
            'item' => $item
        ]);
    }
}