<?php

namespace Site\FrontendBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Site\FrontendBundle\Form\OrderHasSetComponentType;


class OrderHasSetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('number','text',[
                'label'=>'Количество',
                'attr'=>[
                    'class'=>'number-count'
                ],
                'empty_data'=>1
            ])
            ->add('orderHasSetComponents',CollectionType::class,[
                'entry_type'=>OrderHasSetComponentType::class
            ])
            ->add('delete',CheckboxType::class,[
                "label"=>' ',
                'required'=>false
            ]);
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'data_class'=>'Site\BackendBundle\Entity\OrderHasSet',
            'action'=>''
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'frontend_order_has_set_type';
    }
}
