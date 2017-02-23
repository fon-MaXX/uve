<?php

namespace Site\BackendBundle\Form;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Site\BackendBundle\Entity\XmlLoad;

class XmlLoadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'required' => true,
                'constraints'=>array(
                    new NotBlank(),
                    new File(array(
                        'mimeTypes'=>array('text/xml','application/xml'),
                        'mimeTypesMessage'=>'Not XML file loaded!'
                    ))
                ),
                'attr' => array(
                    'class'=>"clear xml-file-validation",
                    'accept'=> 'text/xml'
                )
            ))
        ;
    }
    public function getName()
    {
        return 'site_backend_bundle_load_xml_type';
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => XmlLoad::class,
        ));
    }
}
