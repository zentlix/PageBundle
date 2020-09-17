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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\MainBundle\UI\Http\Web\FormType\AbstractForm;
use Zentlix\MainBundle\UI\Http\Web\FormType\MetaType;
use Zentlix\MainBundle\UI\Http\Web\Type;
use Zentlix\PageBundle\Application\Command\Page\CreateCommand;
use Zentlix\PageBundle\Domain\Page\Repository\PageRepository;

class Form extends AbstractForm
{
    protected EventDispatcherInterface $eventDispatcher;
    protected TranslatorInterface $translator;
    protected SiteRepository $siteRepository;
    protected PageRepository $pageRepository;
    protected string $template;

    public function __construct(EventDispatcherInterface $eventDispatcher,
                                TranslatorInterface $translator,
                                SiteRepository $siteRepository,
                                PageRepository $pageRepository,
                                string $template)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->siteRepository = $siteRepository;
        $this->pageRepository = $pageRepository;
        $this->template = $template;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $command = $builder->getData();

        $templates = [$this->translator->trans('zentlix_main.default_template') => $this->template];
        if($command->site) {
            $pageTemplates = $this->siteRepository->get($command->site)->getTemplate()->getConfigParam('pages') ?? [];
        }

        if(isset($pageTemplates) && count($pageTemplates) > 0) {
            $templates = $pageTemplates;
        }

        $main = $builder->create('main', Type\FormType::class, ['inherit_data' => true, 'label' => 'zentlix_main.main'])
            ->add('title', Type\TextType::class, [
                'label' => 'zentlix_main.title'
            ])
            ->add('code', Type\TextType::class, [
                'label' => 'zentlix_main.symbol_code',
                'required' => false,
                'help' => 'zentlix_page.code_hint'
            ])
            ->add('active', Type\CheckboxType::class, [
                'label' => 'zentlix_page.active'
            ])
            ->add('site', Type\ChoiceType::class, [
                'choices'  => $this->siteRepository->assoc(),
                'label' => 'zentlix_main.site.site',
                'update' => true
            ])
            ->add('template', Type\ChoiceType::class, [
                'choices'  => $templates,
                'label' => 'zentlix_page.page_template'
            ])
            ->add('meta', MetaType::class, ['inherit_data' => true])
            ->add('sort', Type\IntegerType::class, [
                'label' => 'zentlix_main.sort',
                'data' => $command instanceof CreateCommand ? $this->pageRepository->getMaxSort() + 1 : $command->sort,
                'constraints' => [
                    new GreaterThan(['value' => 0, 'message' => $this->translator->trans('zentlix_main.validation.greater_0')])
                ]
            ]);
        $builder->add($main);

        $builder->add(
            $builder->create('content', Type\FormType::class, ['inherit_data' => true, 'label' => 'zentlix_page.content'])
                ->add('content', Type\EditorType::class, ['label' => 'zentlix_page.content'])
        );
    }
}