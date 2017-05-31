<?php

namespace Site\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class SiteFrontendRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('min','hidden',[
                'attr'=>[
                    'class'=>'form-range-min-input'
                ]
            ])
            ->add('max','hidden',[
                'attr'=>[
                    'class'=>'form-range-max-input'
                ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'min' => 0,
            'max' => 0
        ]);
    }
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['min'] = $options['min'];
        $view->vars['max'] = $options['max'];

    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'frontend_range_type';
    }
}
