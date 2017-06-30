<?php

namespace Site\FrontendBundle\Form;

use Site\BackendBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;
class ProductShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(isset($options['form_parameters']['insertionColors'])) {
            $builder->add('insertionColors', ChoiceType::class, [
                'auto_initialize' => false,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Цвет вставки',
                'choices' => array_flip($options['form_parameters']['insertionColors']),
                'attr' => [
                    'class' => "select-default-view insertion-color-select"
                ]
            ]);
        }
        if(isset($options['form_parameters']['ringSizes'])) {
            $builder->add('ringSizes', ChoiceType::class, [
                'auto_initialize' => false,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Размер кольца',
                'choices' => array_flip($options['form_parameters']['ringSizes']),
                'attr' => [
                    'class' => "select-default-view ring-size-select"
                ]
            ]);
        }
        if(isset($options['form_parameters']['chainSizes'])) {
            $builder->add('chainSizes', ChoiceType::class, [
                'auto_initialize' => false,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Длинна цепи',
                'choices' => array_flip($options['form_parameters']['chainSizes']),
                'attr' => [
                    'class' => "select-default-view chain-size-select"
                ]
            ]);
        }
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'form_parameters'=>'',
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'frontend_product_show_type';
    }
}
