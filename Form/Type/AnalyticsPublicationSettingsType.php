<?php

namespace Newscoop\AnalyticsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AnalyticsPublicationSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('piwikUrl', 'text', array(
                'constraints' => new Assert\Regex(array(
                    'pattern' => '/^(http|https|ftp)/',
                    'match'   => false,
                    'message' => 'enter Url without protocol (host name only)',
                    ))
                ))
            ->add('authToken', 'text', array(
                'label' => 'Token',
                'required' => false,
                'empty_data' => 'unknown',
                ))
            ->add('siteId', 'integer', array(
                'constraints' => new Assert\Regex(array(
                    'pattern' => '/^[1-9]{1,45}$/',
                    'match'   => true,
                    'message' => 'numbers greater than 0 only',
                    ))
                ))
            ->add('trackingType', 'choice', array(
                'choices' => array(
                    '0' => 'Default (Piwik JavaScript & ImageTracker)',
                    '1' => 'Piwik JavaScript',
                    '2' => 'Piwik ImageTracker',
                    '3' => 'Google Universal Analytics',
                    '4' => 'Google Classic Analytics',
                )
            ))
            ->add('active', 'checkbox', array(
                'label' => 'Activate', 
                'required' => false,
            ))
            ->add('ipAnonymise', 'checkbox', array(
                'label' => 'Anonymise', 
                'required' => false,
                'disabled' => 'disabled',
            ))
            ->add('piwikPost', 'checkbox', array(
                'label' => 'POST', 
                'required' => false,
            ))
            ->add('send', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Newscoop\AnalyticsBundle\Entity\PublicationSettings',
            ));
    }

    public function getName()
    {
        return 'analyticspublicationsettings';
    }
}
