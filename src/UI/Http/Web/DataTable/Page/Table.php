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
use Omines\DataTablesBundle\DataTable;
use Zentlix\MainBundle\Domain\DataTable\Column\TextColumn;
use Zentlix\MainBundle\Infrastructure\Share\DataTable\AbstractDataTableType;
use Zentlix\PageBundle\Domain\Page\Event\Table as TableEvent;
use Zentlix\PageBundle\Domain\Page\Entity\Page;

class Table extends AbstractDataTableType
{
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable->setName($this->router->generate('admin.page.list'));
        $dataTable->setTitle('zentlix_page.pages');
        $dataTable->setCreateBtnLabel('zentlix_page.create.action');

        $dataTable
            ->add('id', TextColumn::class, ['label' => 'zentlix_main.id', 'visible' => true,])
            ->add('title', TextColumn::class,
                [
                    'render' => fn($value, Page $context) =>
                        sprintf('<a href="%s">%s</a>', $this->router->generate('admin.page.update', ['id' => $context->getId()]), $value),
                    'visible' => true,
                    'label' => 'zentlix_main.title'
                ])
            ->add('code', TextColumn::class, ['label' => 'zentlix_main.symbol_code', 'visible' => true])
            ->add('site', TextColumn::class,
                [
                    'data' => fn(Page $page) => $page->getSite() ? $page->getSite()->getTitle() : $this->translator->trans('zentlix_main.no'),
                    'label' => 'zentlix_main.site.site',
                    'visible' => true
                ])
            ->add('template', TextColumn::class, ['label' => 'zentlix_main.template', 'visible' => false])
            ->add('sort', TextColumn::class, ['label' => 'zentlix_main.sort', 'visible' => true])
            ->addOrderBy($dataTable->getColumnByName('sort'), $dataTable::SORT_ASCENDING)
            ->addOrderBy($dataTable->getColumnByName('id'), $dataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, ['entity' => Page::class]);

        $this->eventDispatcher->dispatch(new TableEvent($dataTable));
    }
}