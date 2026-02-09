<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Groupe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
// use Symfony\Component\Validator\Constraints\Length;
// use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use App\Validator\Constraints\GroupeObligatoire;


class CompteFormType extends AbstractType
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    { 
        $builder
            ->add('prenom', null, [
                'label' => 'Prénom'
            ])
            ->add('nom', null, [
                'label' => 'Nom'
            ])
            ->add('email', null, [
                'label' => 'Email'
            ])
            ->add('telephone', null, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('adresse', null, [
                'label' => 'Adresse'
            ])
            ->add('codePostal', null, [
                'label' => 'Code postal',
                'required' => false
            ])
            ->add('ville', null, [
                'label' => 'Ville'
            ])
            ->add('dateDeNaissance', null, [
                'label' => 'Date de naissance'
            ])
            ->add('compositionFoyer', IntegerType::class, [
                'label' => 'Composition du foyer',
                'property_path' => 'compositionFoyer',
                'required' => false,
                'attr' => ['min' => 1, 'step' => 1],
            ])
            ->add('nombreEnfants', IntegerType::class, [
                'label' => 'Nombre d\'enfants -12 ans',
                'required' => false,
                'empty_data' => '0',
            ])

            // Liste déroulante des groupes existants
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'nom',
                'label' => 'Nom de mon groupe',
                'required' => false
                ])

            // Champ texte pour nouveau groupe            
            ->add('nouveauGroupe', TextType::class, [
                'mapped' => false,   // ⭐ OBLIGATOIRE
                'required' => false,
                'label' => 'Je crée un nouveau groupe',
                'attr' => [
                    'placeholder' => 'Nom du nouveau groupe'
                ],
            ])

            ->add('isReferent', CheckboxType::class, [
                'label' => "Je suis référent·e de mon groupe",
                'required' => false,
            ])
        // ->add('plainPassword', PasswordType::class, [
        //     // TOUJOURS = password est lu et encodé dans le controller et non dans entity
        //     'mapped' => false,
        //     'attr' => ['autocomplete' => 'new-password'],
        // ]);
        // Champ spécial pour changer le mot de passe 
            ->add('newPassword', PasswordType::class, [ 
                'mapped' => false, 
                'required' => false, 
                'label' => 'Nouveau mot de passe', 
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [           // lié aux 2 fichiers src/Validator/Constraints/GroupeObligatoire.php ET GroupeObligatoireValidator.php
                new GroupeObligatoire(), // contrainte personnalisée symfony pour groupe OBLIGATOIRE car à utiliser dans plusieurs formulaires 
            ],                           // avec le use App\Validator\Constraints\GroupeObligatoire;
        ]);   
    }
}
