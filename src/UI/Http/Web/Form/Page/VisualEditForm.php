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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\MainBundle\UI\Http\Web\Form\VisualEditorFormInterface;
use Zentlix\MainBundle\UI\Http\Web\FormType\AbstractForm;
use Zentlix\MainBundle\UI\Http\Web\Type;
use Zentlix\PageBundle\Application\Command\Page\UpdateCommand;
use Zentlix\PageBundle\Domain\Page\Event\VisualEditForm as VisualEditFormEvent;

class VisualEditForm extends AbstractForm implements VisualEditorFormInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private TranslatorInterface $translator;
    private SiteRepository $siteRepository;
    private string $template;

    public function __construct(EventDispatcherInterface $eventDispatcher,
                                TranslatorInterface $translator,
                                SiteRepository $siteRepository,
                                string $template)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->siteRepository = $siteRepository;
        $this->template = $template;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var UpdateCommand $command */
        $command = $builder->getData();

        $templates = [$this->translator->trans('zentlix_main.default_template') => $this->template];
        $pageTemplates = $this->siteRepository->get($command->site)->getTemplate()->getConfigParam('pages') ?? [];

        if($command->site && count($pageTemplates) > 0) {
            $templates = $pageTemplates;
        }

        $builder->add('title', Type\TextType::class, ['label' => 'zentlix_main.title'])
            ->add('content', Type\TextareaType::class, [
            'label' => 'zentlix_page.content',
            'attr' => ['class' => 'cke-editor'],
            ])
            ->add('template', Type\ChoiceType::class, [
                'choices'  => $templates,
                'label' => 'zentlix_page.page_template'
            ])
            ->add('meta_title', Type\TextType::class, [
                'label' => 'zentlix_main.meta_title',
                'required' => false
            ])
            ->add('meta_description', Type\TextareaType::class, [
                'label' => 'zentlix_main.meta_description',
                'required' => false
            ])
            ->add('meta_keywords', Type\TextareaType::class, [
                'label' => 'zentlix_main.meta_keywords',
                'required' => false
            ])
            ->add('sort', Type\IntegerType::class, [
                'label' => 'zentlix_main.sort',
                'constraints' => [
                    new GreaterThan(['value' => 0, 'message' => $this->translator->trans('zentlix_main.validation.greater_0')])
                ]
            ]);

        $this->eventDispatcher->dispatch(new VisualEditFormEvent($builder));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' =>  UpdateCommand::class,
            'label'      => 'zentlix_page.page'
        ]);
    }
}