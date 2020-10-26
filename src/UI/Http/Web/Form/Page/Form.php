<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\UI\Http\Web\Form\Page;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\MainBundle\UI\Http\Web\FormType\AbstractForm;
use Zentlix\MainBundle\UI\Http\Web\FormType\MetaType;
use Zentlix\MainBundle\UI\Http\Web\Type;
use Zentlix\PageBundle\Application\Command\Page\CreateCommand;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;

class Form extends AbstractForm
{
    protected EventDispatcherInterface $eventDispatcher;
    protected SiteRepository $siteRepository;
    protected PageRepository $pageRepository;

    public function __construct(EventDispatcherInterface $eventDispatcher, SiteRepository $siteRepository, PageRepository $pageRepository)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->siteRepository = $siteRepository;
        $this->pageRepository = $pageRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $command = $builder->getData();

        $main = $builder->create('main', Type\FormType::class, ['inherit_data' => true, 'label' => 'zentlix_main.main'])
            ->add('title', Type\TextType::class, [
                'label' => 'zentlix_main.title'
            ])
            ->add('code', Type\TextType::class, [
                'label'    => 'zentlix_main.symbol_code',
                'required' => false,
                'help'     => 'zentlix_page.code_hint'
            ])
            ->add('active', Type\CheckboxType::class, [
                'label' => 'zentlix_page.active'
            ])
            ->add('site', Type\ChoiceType::class, [
                'choices'  => $this->siteRepository->assoc(),
                'label'    => 'zentlix_main.site.site',
                'attr'     => [
                    'class' => 'page-site'
                ]
            ])
            ->add('template', Type\ChoiceType::class, [
                'choices'  => ['yy' => 'page/default.html.twig'],
                'label'    => 'zentlix_page.page_template',
                'attr'     => [
                    'class' => 'page-template'
                ]
            ])
            ->add('meta', MetaType::class, ['inherit_data' => true, 'label' => false])
            ->add('sort', Type\IntegerType::class, [
                'label' => 'zentlix_main.sort',
                'data' => $command instanceof CreateCommand ? $this->pageRepository->getMaxSort() + 1 : $command->sort
            ]);
        $builder->add($main);

        $builder->add(
            $builder->create('content', Type\FormType::class, ['inherit_data' => true, 'label' => 'zentlix_page.content'])
                ->add('content', Type\EditorType::class, ['label' => 'zentlix_page.content'])
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {

            $form = $event->getForm();
            $data = $event->getData();
            $fieldOptions = $form->get('main')->get('template')->getConfig()->getOptions();

            $form->get('main')->add('template', Type\ChoiceType::class,
                array_replace(
                    $fieldOptions, [
                        'choices' => ['template' => $data['main']['template']]
                    ]
                )
            );
        });
    }
}