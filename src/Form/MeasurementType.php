<?php
// src/Form/MeasurementType.php

namespace App\Form;

use App\Entity\Measurement;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options): void
{
$builder
->add('celsius', NumberType::class)
->add('date', DateTimeType::class, [
'widget' => 'single_text',
])
->add('location', EntityType::class, [
'class' => Location::class,
'choice_label' => 'city',
]);
}

public function configureOptions(OptionsResolver $resolver): void
{
$resolver->setDefaults([
'data_class' => Measurement::class,
]);
}
}
