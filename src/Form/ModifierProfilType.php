<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifierProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                "label" => "Votre Pseudo : "
            ])
            ->add('nom', TextType::class, [
                "label" => "Votre Nom : "
            ])
            ->add('prenom', TextType::class, [
                "label" => "Votre Prénom : "
            ])
            ->add('telephone', NumberType::class, [
                "label" => "Votre numéro de portable : "
            ])
            ->add('mail', EmailType::class, [
                "label" => "Votre E-Mail : "
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Votre mot de passe : '),
                'second_options' => array('label' => 'Répéter le mot de passe : '),
                'invalid_message' => 'Vos mots de passe ne concordent pas !'
            ))
            ->add('site', EntityType::class, array('class' => Site::class,
                // Attribut utilisé pour l'affichage
                'choice_label' => 'nom',
                //Fait une requête particulière
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC');
                }
            ))
            ->add("fileTemp", FileType::class, [
                "label" => "Télécharger vers le serveur : ",
                "required" => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
