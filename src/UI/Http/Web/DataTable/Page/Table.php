<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\UI\Http\Web\DataTable\Page;

use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTable;
use Zentlix\MainBundle\Infrastructure\DataTable\AbstractDataTableType;
use Zentlix\PageBundle\Domain\Page\Event\Table as TableEvent;
use Zentlix\PageBundle\Domain\Page\Entity\Page;

class Table extends AbstractDataTableType
{
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable->setName('pages-datatable');

        $dataTable
            ->add('id', TextColumn::class, ['label' => 'zentlix_main.id', 'visible' => true,])
            ->add('title', TwigColumn::class,
                [
                    'template' => '@PageBundle/admin/pages/datatable/title.html.twig',
                    'visible'  => true,
                    'label'    => 'zentlix_main.title'
                ])
            ->add('code', TextColumn::class, ['label' => 'zentlix_main.symbol_code', 'visible' => true])
            ->add('site', TextColumn::class,
                [
                    'data'    => fn(Page $page) => $page->getSite() ? $page->getSite()->getTitle() : $this->translator->trans('zentlix_main.no'),
                    'label'   => 'zentlix_main.site.site',
                    'visible' => true
                ])
            ->add('template', TextColumn::class, ['label' => 'zentlix_main.template', 'visible' => false])
            ->add('sort', TextColumn::class, ['label' => 'zentlix_main.sort', 'visible' => true])
            ->add('active', TwigColumn::class, [
                'template' => '@PageBundle/admin/pages/datatable/active.html.twig',
                'label'    => 'zentlix_page.active',
                'visible'  => true
            ])
            ->addOrderBy($dataTable->getColumnByName('sort'), $dataTable::SORT_ASCENDING)
            ->addOrderBy($dataTable->getColumnByName('id'), $dataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, ['entity' => Page::class]);

        $this->eventDispatcher->dispatch(new TableEvent($dataTable));
    }
}