<?php

namespace Newscoop\PiwikBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PiwikPublicationSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('piwikUrl', 'text')
            ->add('piwikId', 'integer')
            ->add('type', 'choice', array(
                'choices'=>array('1'=>'JavaScript', '2'=>'ImageTracker')))
            ->add('ipAnonymize', 'checkbox', array(
                'label'=>'Anonymize', 'required' => false))
            ->add('piwikPost', 'checkbox', array(
                'label'=>'POST', 'required' => false))
            ->add('send', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Newscoop\PiwikBundle\Entity\PublicationSettings',
            ));
    }

    public function getName()
    {
        return 'piwikpublicationsettings';
    }
}
