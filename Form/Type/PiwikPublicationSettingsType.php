<?php

namespace Newscoop\PiwikBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Range;

class PiwikPublicationSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('piwikUrl', 'text')
            ->add('piwikId', 'integer', array('constraints'=>new Range(array('min'=>1)) ))
            ->add('type', 'choice', array(
                'choices'=>array('JavaScript'=>'JavaScript', 'ImageTracker'=>'ImageTracker')))
            ->add('ipAnonymize', 'checkbox', array(
                'label'=>'Anonymize'))
            ->add('piwikPost', 'checkbox', array(
                'label'=>'POST'))
            ->add('send', 'submit')
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