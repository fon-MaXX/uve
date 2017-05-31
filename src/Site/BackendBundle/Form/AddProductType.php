<?php

namespace Site\BackendBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Site\BackendBundle\Form\ProductType;

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('category',ChoiceType::class,[
                'label'=>'Категория',
                'attr'=>[
                    'class'=>'order-add-product-category'
                ],
                'expanded'=>false,
                'multiple'=>false,
                'required'=>true,
                'choices'=>array_flip($options['choices'])
            ])
            ->add('products',CollectionType::class,[
                'entry_type' => ProductType::class
                ]
            );
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'action'=>'',
            'data_class'=>'Site\BackendBundle\Entity\OrderAddProduct',
            'choices'=>[]
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'backend_add_product_type';
    }
}
