<?php

namespace AppBundle\Form;

use AppBundle\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\DataTransformer\PrayerTransformer;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConfigurationType extends AbstractType {

    /**
     * @var Translator 
     */
    private $translator;

    /**
     * @var Array 
     */
    private static $timezones = [
        "-12" => "(GMT-12:00) International Date Line West",
        "-11" => "(GMT-11:00) Midway Island, Samoa",
        "-10" => "(GMT-10:00) Hawaii",
        "-9" => "(GMT-09:00) Alaska",
        "-8" => "(GMT-08:00) Pacific Time (US & Canada), Tijuana, Baja California",
        "-7" => "(GMT-07:00) Arizona, Chihuahua, La Paz, Mazatlan, Mountain Time (US & Canada)",
        "-6" => "(GMT-06:00) Central Time (US & Canada), Guadalajara, Mexico City, Monterrey, Saskatchewan, ",
        "-5" => "(GMT-05:00) Bogota, Lima, Quito, Rio Branco, Eastern Time (US & Canada), Indiana (East)",
        "-4" => "(GMT-04:00) Atlantic Time (Canada), Caracas, La Paz, Manaus, Santiago",
        "-3.5" => "(GMT-03:30) Newfoundland",
        "-3" => "(GMT-03:00) Brasilia, Buenos Aires, Georgetown, Greenland, Montevideo",
        "-2" => "(GMT-02:00) Mid-Atlantic",
        "-1" => "(GMT-01:00) Cape Verde Is, Azores",
        "0" => "(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London, Casablanca, Monrovia, Reykjavik",
        "1" => "(GMT+01:00) Paris, Brussels, Copenhagen, Madrid, Amsterdam, Berlin, Bern, Rome, West Central Africa",
        "2" => "(GMT+02:00) Amman, Athens, Bucharest, Istanbul, Beirut, Cairo",
        "3" => "(GMT+03:00) Kuwait, Riyadh, Baghdad, Moscow, Nairobi",
        "3.5" => "(GMT+03:30) Tehran",
        "4" => "(GMT+04:00) Abu Dhabi, Muscat",
        "4.5" => "(GMT+04:30) Kabul",
        "5" => "(GMT+05:00) Islamabad, Karachi, Tashkent",
        "5.5" => "(GMT+05:30) Sri Jayawardenapura, Chennai, Kolkata, Mumbai, New Delh",
        "5.75" => "(GMT+05:45) Kathmandu",
        "6" => "(GMT+06:00) Almaty, Novosibirsk",
        "6.5" => "(GMT+06:30) Yangon (Rangoon)",
        "7" => "(GMT+07:00) Bangkok, Hanoi, Jakarta",
        "8" => "(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi, Kuala Lumpur, Singapore",
        "9" => "(GMT+09:00) Osaka, Sapporo, Tokyo, Seoul",
        "9.5" => "(GMT+09:30) Adelaide, Darwin",
        "10" => "(GMT+10:00) Canberra, Melbourne, Sydney",
        "11" => "(GMT+11:00) Magadan, Solomon Is., New Caledonia",
        "12" => "(GMT+12:00) Fiji, Kamchatka, Marshall Is.",
        "13" => "(GMT+13:00) Nuku'alofa"
    ];

    /**
     * @var Array 
     */
    private static $dstChoices = [
        "auto" => 2,
        "disabled" => 0,
        "enabled" => 1
    ];

    /**
     * @var Array
     */
    private static $randomHadithIntervalDisabling = [
        "" => "",
        "fajr-zuhr" => "0-1",
        "zuhr-asr" => "1-2",
        "asr-maghrib" => "2-3",
        "maghrib-isha" => "3-4"
    ];

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('jumuaTime', null, [
                    'label' => 'configuration.form.jumuaTime.label',
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.jumuaTime.title'),
                        'placeholder' => 'hh:mm',
                        'pattern' => '\d{2}:\d{2}',
                        'maxlength' => 5,
                        'oninvalid' => "setCustomValidity('" . $this->translator->trans('configuration.form.time_invalid') . "')",
                        'onchange' => 'try {setCustomValidity("")} catch (e) {}'
                    ]
                ])
                ->add('jumuaAsDuhr', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.jumuaAsDuhr.label',
                ])
                ->add('noJumua', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.noJumua.label',
                ])
                ->add('jumuaDhikrReminderEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.jumuaDhikrReminderEnabled.label',
                    'attr' => [
                        'title' => 'configuration.form.jumuaDhikrReminderEnabled.title',
                    ]
                ])
                ->add('jumuaBlackScreenEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.jumuaBlackScreenEnabled.label',
                ])
                ->add('jumuaTimeout', IntegerType::class, [
                    'label' => 'configuration.form.jumuaTimeout.label',
                    'attr' => [
                        'min' => 0
                    ]
                ])
                ->add('aidTime', null, [
                    'label' => 'configuration.form.aidTime.label',
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.aidTime.title'),
                        'placeholder' => 'hh:mm',
                        'pattern' => '\d{2}:\d{2}',
                        'maxlength' => '5',
                        'oninvalid' => "setCustomValidity('" . $this->translator->trans('configuration.form.time_invalid') . "')",
                        'onchange' => 'try {setCustomValidity("")} catch (e) {}'
                    ]
                ])
                ->add('imsakNbMinBeforeFajr', IntegerType::class, [
                    'label' => 'configuration.form.imsakNbMinBeforeFajr.label',
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.imsakNbMinBeforeFajr.title'),
                        'min' => 0
                    ]
                ])
                ->add('maximumIshaTimeForNoWaiting', null, [
                    'label' => 'configuration.form.maximumIshaTimeForNoWaiting.label',
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.maximumIshaTimeForNoWaiting.title'),
                        'placeholder' => 'hh:mm ex: 22:30',
                        'pattern' => '\d{2}:\d{2}',
                        'maxlength' => '5',
                        'oninvalid' => "setCustomValidity('" . $this->translator->trans('configuration.form.time_invalid') . "')",
                        'onchange' => 'try {setCustomValidity("")} catch (e) {}'
                    ]
                ])
                ->add('waitingTimes', PrayerType::class, [
                    'label' => 'configuration.form.waitingTimes.label',
                    'sub_options' => [
                        'required' => true,
                        'type' => IntegerType::class,
                        'constraints' => new NotBlank(['message' => "form.configuration.mandatory"]),
                        'attr' => [
                            'min' => 0
                        ]
                    ]
                ])
                ->add('adjustedTimes', PrayerType::class, [
                    'required' => true,
                    'label' => 'configuration.form.adjustedTimes.label',
                    'constraints' => new NotBlank(['message' => "form.configuration.mandatory"]),
                    'sub_options' => [
                        'type' => IntegerType::class
                    ]
                ])
                ->add('fixedTimes', PrayerType::class, [
                    'required' => false,
                    'label' => 'configuration.form.fixedTimes.label',
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.fixedTimes.title')
                    ],
                    'sub_options' => [
                        'type' => TextType::class,
                        'attr' => [
                            'placeholder' => "hh:mm",
                            'pattern' => '\d{2}:\d{2}',
                            'maxlength' => 5,
                            'oninvalid' => "setCustomValidity('" . $this->translator->trans('configuration.form.time_invalid') . "')",
                            'onchange' => 'try {setCustomValidity("")} catch (e) {}'
                        ]
                    ]
                ])
                ->add('duaAfterPrayerShowTimes', PrayerType::class, [
                    'label' => 'configuration.form.duaAfterPrayerShowTimes.label',
                    'sub_options' => [
                        'type' => IntegerType::class,
                        'constraints' => new NotBlank(['message' => "form.configuration.mandatory"]),
                        'required' => true,
                        'attr' => [
                            'min' => 5
                        ]
                    ],
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.duaAfterPrayerShowTimes.title'),
                    ],
                ])
                ->add('hijriAdjustment', IntegerType::class, [
                    'label' => 'configuration.form.hijriAdjustment.label'
                ])
                ->add('timezone', ChoiceType::class, [
                    'required' => true,
                    'label' => 'configuration.form.timezone.label',
                    'choices' => array_flip(self::$timezones),
                    'attr' => [
                        'title' => 'configuration.form.timezone.title',
                    ],
                ])
                ->add('dst', ChoiceType::class, [
                    'required' => true,
                    'label' => 'configuration.form.dst.label',
                    'choices' => self::$dstChoices,
                    'choice_translation_domain' => true,
                    'attr' => [
                        'title' => 'configuration.form.dst.title',
                    ],
                ])
                ->add('hadithLang', ChoiceType::class, [
                    'required' => true,
                    'label' => 'configuration.form.hadithLang.label',
                    'choices' => array_combine(Configuration::HADITH_LANG, Configuration::HADITH_LANG),
                    'choice_translation_domain' => true
                ])
                ->add('hijriDateEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.hijriDateEnabled.label',
                ])
                ->add('duaAfterAzanEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.duaAfterAzanEnabled.label',
                ])
                ->add('duaAfterPrayerEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.duaAfterPrayerEnabled.label',
                ])
                ->add('azanBip', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.azanBip.label',
                ])
                ->add('azanVoiceEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.azanVoiceEnabled.label',
                    'attr' => [
                        'title' => 'configuration.form.azanVoiceEnabled.title',
                    ],
                ])
                ->add('iqamaBip', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.iqamaBip.label',
                ])
                ->add('blackScreenWhenPraying', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.blackScreenWhenPraying.label',
                    'attr' => [
                        'title' => 'configuration.form.blackScreenWhenPraying.title',
                    ],
                ])
                ->add('sourceCalcul', ChoiceType::class, [
                    'required' => true,
                    'label' => 'configuration.form.sourceCalcul.label',
                    'choice_translation_domain' => true,
                    'choices' => array_combine(Configuration::SOURCE_CHOICES, Configuration::SOURCE_CHOICES)
                ])
                ->add('prayerMethod', ChoiceType::class, [
                    'required' => true,
                    'label' => 'configuration.form.prayerMethod.label',
                    'choice_translation_domain' => true,
                    'choices' => array_combine(Configuration::METHOD_CHOICES, Configuration::METHOD_CHOICES)
                ])
                ->add('fajrDegree', IntegerType::class, [
                    'required' => false,
                    'label' => 'configuration.form.fajrDegree.label',
                    'attr' => [
                        'placeholder' => 'configuration.form.fajrDegree.placeholder'
                    ]
                ])
                ->add('ishaDegree', IntegerType::class, [
                    'required' => false,
                    'label' => 'configuration.form.ishaDegree.label',
                    'attr' => [
                        'placeholder' => 'configuration.form.ishaDegree.placeholder'
                    ]
                ])
                ->add('iqamaDisplayTime', IntegerType::class, [
                    'label' => 'configuration.form.iqamaDisplayTime.label',
                    'attr' => [
                        'min' => 5
                    ]
                ])
                ->add('wakeForFajrTime', IntegerType::class, [
                    'required' => false,
                    'label' => 'configuration.form.wakeForFajrTime.label',
                    'attr' => [
                        'title' => 'configuration.form.wakeForFajrTime.title',
                    ]
                ])
                ->add('urlQrCodeEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.urlQrCodeEnabled.label',
                    'attr' => [
                        'title' => 'configuration.form.urlQrCodeEnabled.title',
                    ]
                ])
                ->add('smallScreen', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.smallScreen.label',
                    'attr' => [
                        'title' => 'configuration.form.smallScreen.title',
                    ]
                ])
                ->add('ninetyMinBetweenMaghibAndIsha', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.ninetyMinBetweenMaghibAndIsha.label',
                    'attr' => [
                        'title' => 'configuration.form.ninetyMinBetweenMaghibAndIsha.title',
                    ]
                ])
                ->add('randomHadithEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.randomHadithEnabled.label',
                    'attr' => [
                        'title' => 'configuration.form.randomHadithEnabled.title',
                    ]
                ])
                ->add('randomHadithIntervalDisabling', ChoiceType::class, [
                    'required' => false,
                    'choices' => self::$randomHadithIntervalDisabling,
                    'label' => 'configuration.form.randomHadithIntervalDisabling.label',
                    'attr' => [
                        'title' => 'configuration.form.randomHadithIntervalDisabling.title',
                    ]
                ])
                ->add('iqamaEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.iqamaEnabled.label',
                    'attr' => [
                        'title' => 'configuration.form.iqamaEnabled.title',
                    ]
                ])
                ->add('temperatureEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.temperatureEnabled.label',
                    'attr' => [
                        'title' => 'configuration.form.temperatureEnabled.title',
                    ]
                ])
                ->add('backgroundColor', null, [
                    'label' => 'configuration.form.backgroundColor.label',
                ])
                ->add('calendar')
                ->add('timeToDisplayMessage', IntegerType::class, [
                    'required' => false,
                    'constraints' => new NotBlank(['message' => "form.configuration.mandatory"]),
                    'label' => 'configuration.form.timeToDisplayMessage.label',
                    'attr' => [
                        'min' => 5,
                        'title' => 'configuration.form.timeToDisplayMessage.title',
                    ]
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'save',
                    'attr' => [
                        'class' => 'btn btn-lg btn-primary',
                    ]
                ]);

        $builder->get('waitingTimes')
                ->addModelTransformer(new PrayerTransformer(IntegerType::class));
        $builder->get('adjustedTimes')
                ->addModelTransformer(new PrayerTransformer(IntegerType::class));
        $builder->get('fixedTimes')
                ->addModelTransformer(new PrayerTransformer(TimeType::class));
        $builder->get('duaAfterPrayerShowTimes')
                ->addModelTransformer(new PrayerTransformer(IntegerType::class));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Configuration::class,
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_configuration';
    }

}
