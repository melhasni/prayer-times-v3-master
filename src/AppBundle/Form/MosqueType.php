<?php

namespace AppBundle\Form;

use AppBundle\Entity\Mosque;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MosqueType extends AbstractType
{

    /**
     *
     * @var AuthorizationChecker
     */
    private $securityChecker;

    /**
     *
     * @var EntityManager
     */
    private $em;

    public function __construct(AuthorizationChecker $securityChecker, EntityManager $em)
    {
        $this->securityChecker = $securityChecker;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $builder->getData()->getUser();
        if ($user instanceof User && $this->securityChecker->isGranted('ROLE_ADMIN')) {
            $builder
                ->add('user', HiddenType::class, [
                    'required' => true,

                ])
                ->add('user_complete', TextType::class, [
                    'data' => $user->getEmail(),
                    'label' => 'user',
                    'mapped' => false
                ]);

            $builder->get('user')
                ->addModelTransformer(new CallbackTransformer(
                    function ($user) {
                        return $user->getId();
                    },
                    function ($id) {
                        return $this->em->getRepository("AppBundle:User")->find($id);
                    }
                ));
        }


        $builder
            ->add('name', null, [
                'label' => 'mosque.name',
                'required' => true,
                'attr' => [
                    'placeholder' => 'mosque.form.name.placeholder',
                ]
            ])
            ->add('type', ChoiceType::class, [
                'required' => true,
                'label' => 'mosque.form.type.label',
                'choices' => array_combine([
                    "mosque.types.mosque",
                    "mosque.types.home",
                ], Mosque::TYPES)
            ])
            ->add('slug', null, [
                'label' => 'mosque.slug',
                'required' => true,
                'label' => 'mosque.form.slug.label'
            ])
            ->add('associationName', null, [
                'label' => 'association_name',
            ])
            ->add('phone', null, [
                'label' => 'phone'
            ])
            ->add('email', EmailType::class, [
                'label' => 'email.text',
                'required' => false,
            ])
            ->add('site', null, [
                'label' => 'site',
                'required' => false,
            ])
            ->add('address', null, [
                'label' => 'address',
                'attr' => [
                    'placeholder' => 'mosque.form.address.placeholder',
                    'help' => 'mosque.form.address.help',
                ]
            ])
            ->add('city', null, [
                'label' => 'city',
                'required' => true,
                'attr' => [
                    'placeholder' => 'mosque.form.city.placeholder',
                ]
            ])
            ->add('zipcode', null, [
                'label' => 'zipcode',
                'required' => true,
                'attr' => [
                    'placeholder' => 'mosque.form.zipcode.placeholder',
                ]
            ])
            ->add('country', null, [
                'label' => 'country',
                'required' => true,
            ])
            ->add('rib', null, [
                'label' => 'rib',
                'attr' => [
                    'placeholder' => 'mosque.form.rib.placeholder',
                ]
            ])
            ->add('addOnMap', CheckboxType::class, [
                'label' => 'mosque.form.addOnMap.label',
                'required' => false
            ])
            ->add('file1', ImageType::class)
            ->add('file2', ImageType::class)
            ->add('file3', ImageType::class)
            ->add('save', SubmitType::class, [
                'label' => 'save',
                'attr' => [
                    'class' => 'btn btn-lg btn-primary',
                ]
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSetData'));
    }

    public function onPostSetData(FormEvent $event) {
        /** @var Mosque $mosque */
        $mosque = $event->getData();

        if ($mosque->getType() === "home"){
          $mosque->setAddOnMap(false);
        }
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Mosque::class
        ));
    }

    public function getBlockPrefix(){
        return '';
    }

}
