<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class PrayerType extends AbstractType {

    /**
     *
     * @var Translator 
     */
    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $type = $options['sub_options']['type'];
        unset($options['sub_options']['type']);

        $builder
                ->add('fajr', $type, array_merge($options['sub_options'], [
                    'label' => 'fajr'
                ]))
                ->add('zuhr', $type, array_merge($options['sub_options'], [
                    'label' => 'zuhr'
                ]))
                ->add('asr', $type, array_merge($options['sub_options'], [
                    'label' => 'asr'
                ]))
                ->add('maghrib', $type, array_merge($options['sub_options'], [
                    'label' => 'maghrib'
                ]))
                ->add('isha', $type, array_merge($options['sub_options'], [
                    'label' => 'isha'
                ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {

        $resolver->setDefaults(array(
            'sub_options' => []
        ));
    }

}
