<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PhoneNumberValidationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onFormEventsPRESUBMIT',
        ];
    }

    public function onFormEventsPRESUBMIT(FormEvent $event): void
    {
        $data = $event->getData();
        $form = $event->getForm();
        
        if (isset($data['countryCode'], $data['phoneNumber'])) {
            $countryCode = $data['countryCode'];
            $constraints = [];

            switch ($countryCode) {
                case '216': // Tunisia
                    $constraints = [
                        new NotBlank(['message' => 'Please provide a phone number for Tunisia.']),
                        new Regex([
                            'pattern' => '/^[234579][0-9]{7}$/',
                            'message' => 'Please provide a valid Tunisian phone number.',
                        ]),
                    ];
                    break;
                    case '213': // Algeria
                        $constraints = [
                            new NotBlank(['message' => 'Please provide a phone number for Algeria.']),
                            new Regex([
                                'pattern' => '/^[567][0-9]{8}$/',
                                'message' => 'Please provide a valid Algerian phone number.',
                            ]),
                        ];
                        break;
                    case '20': // Egypt
                        $constraints = [
                            new NotBlank(['message' => 'Please provide a phone number for Egypt.']),
                            new Regex([
                                'pattern' => '/^[1-9][0-9]{8}$/',
                                'message' => 'Please provide a valid Egyptian phone number.',
                            ]),
                        ];
                        break;
                        case '33': // France
                            $constraints = [
                                new NotBlank(['message' => 'Please provide a phone number for France.']),
                                new Regex([
                                    'pattern' => '/^[1-9][0-9]{8}$/',
                                    'message' => 'Please provide a valid French phone number.',
                                ]),
                            ];
                            break;
                        case '49': // Germany
                            $constraints = [
                                new NotBlank(['message' => 'Please provide a phone number for Germany.']),
                                new Regex([
                                    'pattern' => '/^[1-9][0-9]{10,11}$/',
                                    'message' => 'Please provide a valid German phone number.',
                                ]),
                            ];
                            break;
                        case '30': // Greece
                            $constraints = [
                                new NotBlank(['message' => 'Please provide a phone number for Greece.']),
                                new Regex([
                                    'pattern' => '/^[2-69][0-9]{9}$/',
                                    'message' => 'Please provide a valid Greek phone number.',
                                ]),
                            ];
                            break;
                            case '39': // Italy
                                $constraints = [
                                    new NotBlank(['message' => 'Please provide a phone number for Italy.']),
                                    new Regex([
                                        'pattern' => '/^[3-9][0-9]{9}$/',
                                        'message' => 'Please provide a valid Italian phone number.',
                                    ]),
                                ];
                                break;
                            case '961': // Lebanon
                                $constraints = [
                                    new NotBlank(['message' => 'Please provide a phone number for Lebanon.']),
                                    new Regex([
                                        'pattern' => '/^[3-9][0-9]{7}$/',
                                        'message' => 'Please provide a valid Lebanese phone number.',
                                    ]),
                                ];
                                break;
                            case '218': // Libya
                                $constraints = [
                                    new NotBlank(['message' => 'Please provide a phone number for Libya.']),
                                    new Regex([
                                        'pattern' => '/^[2-9][0-9]{8}$/',
                                        'message' => 'Please provide a valid Libyan phone number.',
                                    ]),
                                ];
                                break;
                                case '212': // Morocco
                                    $constraints = [
                                        new NotBlank(['message' => 'Please provide a phone number for Morocco.']),
                                        new Regex([
                                            'pattern' => '/^[5-9][0-9]{8}$/',
                                            'message' => 'Please provide a valid Moroccan phone number.',
                                        ]),
                                    ];
                                    break;
                                case '34': // Spain
                                    $constraints = [
                                        new NotBlank(['message' => 'Please provide a phone number for Spain.']),
                                        new Regex([
                                            'pattern' => '/^[6-9][0-9]{8}$/',
                                            'message' => 'Please provide a valid Spanish phone number.',
                                        ]),
                                    ];
                                    break;
                                    case '44': // UK
                                        $constraints = [
                                            new NotBlank(['message' => 'Please provide a phone number for the UK.']),
                                            new Regex([
                                                'pattern' => '/^[1-9][0-9]{9,10}$/',
                                                'message' => 'Please provide a valid UK phone number.',
                                            ]),
                                        ];
                                        break;
                                    case '971': // United Arab Emirates
                                        $constraints = [
                                            new NotBlank(['message' => 'Please provide a phone number for the UAE.']),
                                            new Regex([
                                                'pattern' => '/^[2-9][0-9]{8}$/',
                                                'message' => 'Please provide a valid UAE phone number.',
                                            ]),
                                        ];
                                        break;
                                    case '1': // USA
                                        $constraints = [
                                            new NotBlank(['message' => 'Please provide a phone number for the USA.']),
                                            new Regex([
                                                'pattern' => '/^[2-9][0-9]{9}$/',
                                                'message' => 'Please provide a valid USA phone number.',
                                            ]),
                                        ];
                                        break;
                // Add more cases as needed
            }

            if ($form->has('phoneNumber')) {
                $form->remove('phoneNumber');
                $form->add('phoneNumber', TextType::class, [
                    'constraints' => $constraints,
                    'attr' => ['class' => 'form-control', 'style' => 'width: 100%; margin-right: 20px;'],
                    'label' => false,
                ]);
            }
        }
    }
}
