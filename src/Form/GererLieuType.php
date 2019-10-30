<?php

namespace App\Form;

use App\Entity\Lieu;

use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GererLieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                "label" => "Nom",
                // Permet de pouvoir changer le mail
                "disabled" => false
            ])
            ->add('rue', TextType::class, [
                "label" => "Rue ",
                // Permet de pouvoir changer le mail
                "disabled" => false
            ])
            -> add('idVille', EntityType::class, [
                'label' => 'Ville',
                'class' => Ville::class,
                'choice_label' => 'nom',
                'error_bubbling' => true,
                'placeholder' => 'Sélectionnez une ville',
            ])
            ->add('latitude', NumberType::class, [
                "label" => "Latitude",
                // Permet de pouvoir changer le mail
                "disabled" => false
            ])
            ->add('longitude', NumberType::class, [
                "label" => "Longitude",
                // Permet de pouvoir changer le mail
                "disabled" => false
            ])

            ->add('save', SubmitType::class ,[
            'label' => 'Enregistrer'
            ]);
     }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
