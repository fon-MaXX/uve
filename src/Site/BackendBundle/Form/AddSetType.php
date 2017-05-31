<?php

namespace Site\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AddSetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sets',CollectionType::class,[
            'entry_type' => SetType::class
        ]
    );
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults([
            'action'=>'',
            'data_class'=>'Site\BackendBundle\Entity\OrderAddSet',
        ]);
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'backend_add_set_type';
    }
}
