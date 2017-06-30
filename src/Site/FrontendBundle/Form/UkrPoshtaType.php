<?php
namespace Site\FrontendBundle\Form;

use Site\BackendBundle\Entity\NovaPoshtaData;
use Site\BackendBundle\Entity\NovaPoshtaRegion;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UkrPoshtaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address','textarea',[
            'label'=>"Адрес для отправки",
            'attr'=>[
                'required'=>false,
                'class'=>'order-personal-data-textarea'
            ]
        ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'=>'Site\BackendBundle\Entity\UkrPoshtaData',
            ]
        );
    }
    public function getName()
    {
        return 'site_frontend_ukr_poshta_type';
    }
}
