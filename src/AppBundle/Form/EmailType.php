<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder
                ->add('subject', null, [
                    'label' => 'subject',
                    'required' => true,
                ])
                ->add('content', TextareaType::class, [
                    'label' => 'content',
                    'required' => true,
                    'attr' => [
                        'class' => "tinymce",
                        'rows' => "10"
                    ],
                ])
                ->add('send', SubmitType::class, [
                    'label' => 'send',
                    'attr' => [
                        'class' => 'btn  btn-lg btn-primary',
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
    }

}
