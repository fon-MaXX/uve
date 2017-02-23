<?php

namespace Site\UploadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class UpbeatUploadType extends AbstractType
{
    private $secureToken;
    public function __construct($csrfProvider, \Symfony\Component\HttpFoundation\Session\SessionInterface $session)
    {
        $this->secureToken = $csrfProvider->getToken('')->getValue();
        $session->set('secure_token', $this->secureToken);
    }


    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['file_type'] = $options['file_type'];
        $view->vars['template'] = $options['template'];
        $view->vars['extensions'] = $options['extensions'];
//        $view->vars['multi_selection'] = $options['multi_selection'];
        $view->vars['btn_name'] = $options['btn_name'];
        $view->vars['secure_token'] = $this->secureToken;
        $view->vars['use_plupload'] = $options['use_plupload'];
        $view->vars['use_jqueryupload'] = $options['use_jqueryupload'];
        $view->vars['crop'] = $options['crop'];
        $view->vars['crop_width'] = $options['crop_width'];
        $view->vars['crop_height'] = $options['crop_height'];
    }
    public function configureOptions(OptionsResolver  $resolver) {
        $resolver->setDefaults(array(
            'compound' => false,
            'multi_selection' => false,
            'class' => 'upload',
            'btn_name' => 'Upload',
            'file_type' => 'default',
            'template' => 'SiteUploadBundle:Upload:default.html.twig',
            'extensions' => '',
            'use_plupload' => true,
            'use_jqueryupload' => false,
            'crop' => false,
            'crop_width'=>null,
            'crop_height'=>null
        ));
    }
    public function getParent()
    {
        return 'form';
    }
    public function getName()
    {
        return 'upbeat_upload_type';
    }
}
