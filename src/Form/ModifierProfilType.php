<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ModifierProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                "label" => "Votre Pseudo : ",
                'error_bubbling' => true
            ])
            ->add('nom', TextType::class, [
                "label" => "Votre Nom : ",
                'error_bubbling' => true
            ])
            ->add('prenom', TextType::class, [
                "label" => "Votre Prénom : ",
                'error_bubbling' => true
            ])
            ->add('telephone', TextType::class, [
                "label" => "Votre numéro de portable : "
            ])
            ->add('mail', EmailType::class, [
                "label" => "Votre E-Mail : ",
                // Permet de ne pas pouvoir changer le mail
                "disabled" => true
            ])
            ->add('site', EntityType::class, array('class' => Site::class,
                // Attribut utilisé pour l'affichage
                'choice_label' => 'nom',
                // Permet de ne pas pouvoir changer le site.
                "disabled" => true,
                //Fait une requête particulière
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC');
                }
            ))
            ->add('imageFile', VichImageType::class, [
                "label" => "Télécharger vers le serveur : ",
                "required" => false,
                'error_bubbling' => true,
                'download_uri' => false,
                'image_uri' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'error_bubbling' => true
        ]);
    }
}
