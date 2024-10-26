<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['new', 'edit'],
                        'message' => 'Miasto nie może być puste.',
                    ]),
                    new Assert\Length([
                        'max' => 100,
                        'groups' => ['new', 'edit'],
                        'maxMessage' => 'Nazwa miasta nie może przekraczać 100 znaków.',
                    ]),
                ],
            ])
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'Polska' => 'PL',
                    'Niemcy' => 'DE',
                    'Francja' => 'FR',
                    'Hiszpania' => 'ES',
                    // Można dodać więcej krajów
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['new', 'edit'],
                        'message' => 'Proszę wybrać kraj.',
                    ]),
                ],
                'placeholder' => 'Wybierz kraj',
            ])
            ->add('latitude', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['new'],
                        'message' => 'Szerokość geograficzna nie może być pusta.',
                    ]),
                ],
            ])
            ->add('longitude', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['new'],
                        'message' => 'Długość geograficzna nie może być pusta.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
            'validation_groups' => function ($form) {
                return $form->getData()->getId() ? ['edit'] : ['new'];
            },
        ]);
    }
}
