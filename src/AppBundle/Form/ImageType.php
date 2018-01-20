<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\Image;

class ImageType extends AbstractType {

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'required' => false,
            'translation_domain' => 'messages',
            'download_uri' => false,
            'label' => 'message.form.image.label',
            'attr' =>  ['class' => 'form-control'],
            'constraints' => new Image([
                "allowPortrait" => false,
                "maxSize" => "10M"
            ])
        ]);
    }

    public function getParent()
    {
        return VichImageType::class;
    }

}
