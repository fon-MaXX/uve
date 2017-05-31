<?php

namespace Site\BackendBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addProduct',CheckboxType::class,[
                'label'=>' ',
                'required'=>false
            ]);
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'data_class'=>'Site\BackendBundle\Entity\Product',
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'backend_product_type';
    }
}
