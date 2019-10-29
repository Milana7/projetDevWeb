<?php

namespace App\Form;

use App\Entity\FiltreSortie;
use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class FiltreSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomSite', EntityType::class,
                [
                    'label' => 'Site',
                    'choice_label' => 'nom',
                    'class' => Site::class,
                    'required' => false,
                    'placeholder' => 'Sélectionnez un site',
                ])
            ->add('nomSortie', TextType::class,
                [
                    'label' => 'Le nom de la sortie contient:',
                    'required' => false,
                ])
            ->add('dateDebut', DateType::class,
                [
                    'label' => 'Entre',
                    'required' => false,
                    'widget' => 'single_text',
                ])
            ->add('datefin', DateType::class,
                [
                    'label' => ' et',
                    'required' => false,
                    'widget' => 'single_text',
                ])
            ->add('mesSortiesOrg', CheckboxType::class,
                [
                    'label' => 'Sorties dont je suis l\'organisateur/trice.',
                    'required' => false,
                ])
            ->add('mesSortiesInscr', CheckboxType::class,
                [
                    'label' => 'Sorties auxquelles je suis inscrit/e.',
                    'required' => false,
                ])
            ->add('sortiesNonInscr', CheckboxType::class,
                [
                    'label' => 'Sorties auxquelles je ne suis pas inscrit/e.',
                    'required' => false,
                ])
            ->add('sortiesExpirees', CheckboxType::class,
                [
                    'label' => 'Sorties passées.',
                    'required' => false,
                ])
            ->add('Rechercher', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FiltreSortie::class,
        ]);
    }
}
