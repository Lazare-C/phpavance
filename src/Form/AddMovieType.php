<?php
/*
 * Copyright (C) CHEVEREAU Lazare - All Rights Reserved
 *
 * @project    phpavance
 * @file       AddMovieType.php
 * @author     CHEVEREAU Lazare
 * @date       20/01/2022 11:59
 */

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddMovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom du film:'])
            ->add('score', IntegerType::class, ['label' => 'Votre score'])
            ->add('addBy', EmailType::class, ['label' => "Votre adresse email"])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
