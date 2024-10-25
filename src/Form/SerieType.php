<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            //quand champs pas renseignés, il arrive trouver types
                //le fait de renseigner des options (comem changer le label)
                //écrase le fait que le champs n'est pas requis
            ->add('name', TextType::class, [
                'label' => 'Nom de la série',
                'required' => true
            ])
            ->add('overview', TextareaType::class, [
                'label' => 'Description de la série :',
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'choices'=> [
                    'returning' => 'returning',
                    'ended' => 'ended',
                    'canceled' => 'canceled',
            ],
                'label' => 'Statut de la série',
                'placeholder' => '-- Veuillez sélectionner un statut --',
                'expanded' => true,
                ])
            ->add('vote', NumberType::class, [
                'label' => 'Vote :'
            ])
            ->add('popularity', NumberType::class, [
                'label' => 'Popularité :',
                'required' => false
            ])
            ->add('genres', TextType::class, [
                'label' => 'Genre(s) des séries :',
                'required' => true

            ])
            ->add('backdrop', TextType::class, [
                'label' => 'URL backdrop :',
                'required' => false
            ])
            ->add('poster', TextType::class, [
                'label' => 'URL poster :',
                'required' => false
            ])
            ->add('firstAirDate', null, [
                'label' => '1ère date de diffusion :',
                'widget' => 'single_text'
            ])
            ->add('lastAirDate', null, [
                'label' => 'Dernière date de diffusion :',
                'widget' => 'single_text',
                'required' => false
            ])
            -> add('submit', SubmitType::class, [
                'label'  => 'Soumettre'
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
