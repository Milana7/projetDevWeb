<?php

namespace App\Form;

use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreerSortieType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        dump($builder);
        dump($options);

        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie',
                'error_bubbling' => true
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie',
                'widget' => 'single_text',
                'html5' => true,
                'error_bubbling' => true,
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text',
                'html5' => true,
                'error_bubbling' => true
            ])
            ->add('nbInscriptionsMax', NumberType::class, [
                'label' => 'Nombre de places'
            ])
            ->add('duree', NumberType::class, [
                'label'=>'Durée',
                'required' => false,
                'error_bubbling' => true
            ])
            ->add('infosSortie', TextareaType::class ,[
                'label' => 'Description et infos',
                'required' => false,
                'error_bubbling' => true
            ])
            ->add('villeOrganisatrice', TextType::class, [
                'mapped' => false,
                'label' => 'Ville organisatrice'
            ])
            -> add('villes', EntityType::class, [
                'mapped' => false,
                'label' => 'Ville',
                'class' => Ville::class,
                'choice_label' => 'nom',
                'error_bubbling' => true,
                'placeholder' => 'Sélectionnez une ville',
            ])
            ->add('idLieu', HiddenType::class, [
                'error_bubbling' => true
            ])
            ->add('save', SubmitType::class ,[
                'label' => 'Enregistrer'
            ])
            ->add('publish', SubmitType::class ,[
                'label' => 'Publier'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'error_bubbling' => true
        ]);
    }
}
