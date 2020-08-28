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
use Zentlix\MainBundle\UI\Http\Web\Type\TextType;
use Zentlix\PageBundle\Application\Command\Page\UpdateCommand;
use Zentlix\PageBundle\Domain\Page\Event\UpdateForm as UpdateFormEvent;

class UpdateForm extends Form
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $code = $builder->get('main')->get('code');
        $builder->get('main')->add('code', TextType::class, array_replace($code->getOptions(), ['required' => true]));

        $this->eventDispatcher->dispatch(new UpdateFormEvent($builder));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'     =>  UpdateCommand::class,
            'label'          => 'zentlix_page.update.process',
            'form'           =>  self::TABS_FORM,
            'deleteBtnLabel' => 'zentlix_page.delete.action',
            'deleteConfirm'  => 'zentlix_page.delete.confirmation'
        ]);
    }
}