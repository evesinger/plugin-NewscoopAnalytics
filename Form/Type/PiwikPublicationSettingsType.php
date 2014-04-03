<?php

namespace Newscoop\PiwikBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PiwikPublicationSettingsType extends AbstractType
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
            ->add('piwikId', 'integer', array(
                'constraints' => new Assert\Regex(array(
                    'pattern' => '/^[1-9]{1,45}$/',
                    'match'   => true,
                    'message' => 'numbers greater than 0 only',
                    ))
                ))
            ->add('type', 'choice', array(
                'choices' => array(
                    '0' => 'Default (JavaScript and ImageTracker)',
                    '1' => 'JavaScript',
                    '2' => 'ImageTracker'
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
            'data_class' => 'Newscoop\PiwikBundle\Entity\PublicationSettings',
            ));
    }

    public function getName()
    {
        return 'piwikpublicationsettings';
    }
}
