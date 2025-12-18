<?php

namespace App\Form;

use App\Entity\Adhesion;
use App\Entity\User;
use App\Entity\Groupe;
use App\Entity\Saison;
use App\Entity\MontantAdhesion;
use App\Entity\Motivation;
use App\Entity\Dispo;
use App\Entity\Pole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use App\Validator\Constraints\GroupeObligatoire;


class AdhesionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Choisir un autre groupe existant
            ->add('changeGroupe', EntityType::class, [
                'mapped' => false,
                'label' => 'Je change de groupe',
                'class' => Groupe::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Pour un autre groupe existant',
            ])
            // Créer un nouveau groupe
            ->add('nouveauGroupe', TextType::class, [
                'mapped' => false,
                'label' => 'Je crée un nouveau groupe',
                'required' => false,
                'attr' => ['placeholder' => 'Nom du nouveau groupe'],
            ])
            ->add('adresseDistribNouveau', TextType::class, [
                'mapped' => false,
                'label' => 'Adresse de distribution du nouveau groupe',
                'required' => false,
            ])
            ->add('villeNouveau', TextType::class, [
                'mapped' => false,
                'label' => 'Ville du nouveau groupe',
                'required' => false,
            ])
            // Montant adhésion
            ->add('montantAdhesion', EntityType::class, [
                'class' => MontantAdhesion::class,
                'choice_label' => 'libelle',
                'label' => 'Montant de l’adhésion',
                'placeholder' => 'Choisir un montant',
                'required' => true,
            ])
            // Référent
            ->add('isReferent', CheckboxType::class, [
                'label' => 'Je suis référent·e de mon groupe',
                'mapped' => false,
                'required' => false,
            ])
            // Groupe ouvert
            ->add('isOpen', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Mon groupe peut accueillir de nouveaux adhérent·es',
            ])
            // Adresse distribution
            ->add('adresseDistrib', TextType::class, [
                'label' => "Modification de l'adresse de distribution du groupe",
                'required' => false,
                'mapped' => false,
                'attr' => ['placeholder' => 'Lieu de distribution des commandes'],
            ])
            // Ville
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => false,
                'mapped' => false,
            ])
            // Motivations
            ->add('motivations', EntityType::class, [
                'class' => Motivation::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'Quelles sont tes principales motivations pour rejoindre le GAS ?',
            ])
            // Attentes
            ->add('attentesTexte', TextareaType::class, [
                'required' => false,
                'label' => 'Décris tes attentes spécifiques',
                'attr' => ['placeholder' => 'Exprimes tes attentes, besoins particuliers, suggestions...'],
            ])
            // Disponibilités
            ->add('dispos', EntityType::class, [
                'class' => Dispo::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'Disponibilités pour participer à la vie du GAS',
            ])
            // Compétences
            ->add('competencesTexte', TextareaType::class, [
                'required' => false,
                'label' => 'Quelles compétences particulières peux-tu partager ?',
                'attr' => ['placeholder' => 'Comptabilité, informatique, transport, relations producteurs, etc.'],
            ])
            // Pôles
            ->add('poles', EntityType::class, [
                'class' => Pole::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            // Consentements
            ->add('agree_fonctionnement', CheckboxType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Je m’engage à respecter les règles et participer activement',
            ])
            ->add('agree_adhesion', CheckboxType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Je règle mon adhésion annuelle par virement dans les 15 jours - IBAN FR 0000 0000 0000 0000 000',
            ])
            // Paiement admin
            ->add('paiement', CheckboxType::class, [
                'required' => false,
                'label' => 'Paiement validé (JUSTE POUR admin)',
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adhesion::class,
            'constraints' => [
                new GroupeObligatoire(),
            ],
        ]);
        $resolver->setRequired('user');
    }
}
// class AdhesionFormType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder      
//             // je change de groupe
//             ->add('changeGroupe', EntityType::class, [
//                 'mapped' => false, // pas mappé sur adhesion
//                 'label' => 'Je change de groupe',
//                 'class' => Groupe::class,
//                 'choice_label' => 'nom',
//                 'required' => false, // ← Important !
//                 'placeholder' => 'pour un autre groupe existant',
//                 // 'attr' => ['class' => 'change-groupe-checkbox'],
//             ])
//             // Je crée un nouveau groupe/ TextType pas EntityType car pas de champ pour nouveau groupe
//             ->add('nouveauGroupe', TextType::class, [
//                 'mapped' => false, // pas mappé sur adhesion
//                 'label' => 'Je crée un nouveau groupe',
//                 'required' => false,
//                 'attr' => ['class' => 'nom du nouveau groupe'],
//                 // 'attr' => ['class' => 'nom du nouveau groupe', 'style' => 'display:none;'],
//             ])
//             ->add('isReferent', CheckboxType::class, [
//                 'label' => 'Je suis référent·e de mon groupe',
//                 'mapped' => false, // pas mappé sur adhesion
//                 'required' => false,
//             ])          
//             ->add('isOpen', CheckboxType::class, [
//                 'mapped' => false,// pas mappé sur adhesion
//                 'required' => false,
//                 'label' => 'Mon groupe peut accueillir de nouvelleaux adhérent.es',
//                 'attr' => ['class' => 'is-open-checkbox'],
//                 // 'attr' => ['class' => 'is-open-checkbox', 'style' => 'display:none;'],
//             ])
//             ->add('adresseDistribution', TextType::class, [
//                 'label' => 'Adresse de distribution du groupe',
//                 'required' => false,
//                 'mapped' => false, // pas mappé sur adhesion
//                 'attr' => [
//                     'placeholder' => 'Lieu de distribution des commandes',
//                     'class' => 'referent-field',
//                 ]
//             ])
//             ->add('ville', TextType::class, [
//                 'label' => 'Ville',
//                 'required' => false,
//                 'mapped' => false, // pas mappé sur adhesion
//                 'attr' => ['class' => 'referent-field']
//             ])
//             // ManyToMany
//             ->add('motivations', EntityType::class, [
//                 'class' => Motivation::class,
//                 'choice_label' => 'libelle',   // le texte affiché à côté de chaque case
//                 'multiple' => true,          // plusieurs choix possibles
//                 'expanded' => true,          // affiché en checkboxes (et pas en <select>)
//                 'required' => false,
//                 'label' => 'Quelles sont tes principales motivations pour rejoindre le GAS ?',
//             ])
//            // Texte libre
//             ->add('attentesTexte', TextareaType::class, [
//                 'required' => false,
//                 'label' => 'Décris tes attentes spécifiques (types de produits, fréquence, etc ..)',
//                 'attr' => ['placeholder' => 'Exprimes tes attentes, besoins particuliers, suggestions...'],
//             ])
//             ->add('dispos', EntityType::class, [
//                 'class' => Dispo::class,
//                 'choice_label' => 'libelle',
//                 'multiple' => true,
//                 'expanded' => true,
//                 'required' => false,
//                 'label' => 'Pour la pérénité de l’asso en 100% bénévole, chaque groupe est tenu de participer 
//                 au fonctionnement du GAS à hauteur de 10h annuelles par adhérent.e.s - groupes de travail, CA, livraisons',
//             ])
//             ->add('competencesTexte', TextareaType::class, [
//                 'required' => false,
//                 'label' => 'Quelles compétences particulières peux-tu partager ?',
//                 'attr' => ['placeholder' => 'Comptabilité, informatique, transport, relations producteurs, etc.'],
//             ])
//             ->add('poles', EntityType::class, [
//                 'class' => Pole::class,
//                 'choice_label' => 'nom', // pas de label!
//                 'placeholder' => 'Pôle(s) de travail auquel(s) je souhaite participer',
//                 'multiple' => true,
//                 'expanded' => true,
//                 'required' => false,
//             ])
//             // Consentement requis mais pas mappé/enregistré
//             ->add('agree_fonctionnement', CheckboxType::class, [
//                 'mapped' => false,
//                 'required' => true,
//                 'label' => 'Je m’engage à respecter les règles et participer activement',
//             ])
//             // Consentement requis mais pas mappé/enregistré
//             ->add('agree_adhesion', CheckboxType::class, [
//                 'mapped' => false,
//                 'required' => true,
//                 'label' => 'Je règle mon adhésion annuelle par virement dans les 15 jours',
//             ])
//             ->add('montantAdhesion', EntityType::class, [
//                 'class' => MontantAdhesion::class,
//                 'choice_label' => 'libelle',
//                 'label' => 'Montant de l’adhésion',
//                 'placeholder' => 'Choisir un montant',
//                 'required' => true,
//             ])
//             // Paiement  JUSTE POUR admin
//             ->add('paiement', CheckboxType::class, [
//                 'required' => false,
//                 'label' => 'Paiement validé (JUSTE POUR admin)',
//             ]);

            

//         $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//             $adhesion = $event->getData();
//             $form = $event->getForm();
//         });
//     }


//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => Adhesion::class, // ← LIEN ENTITÉ/FORMULAIRE pour mapper les data vers adhesion
//             'constraints' => [           // lié aux 2 fichiers src/Validator/Constraints/GroupeObligatoire.php ET GroupeObligatoireValidator.php
//                 new GroupeObligatoire(), // contrainte personnalisée symfony pour groupe OBLIGATOIRE car à utiliser dans plusieurs formulaires 
//             ],                           // avec le use App\Validator\Constraints\GroupeObligatoire;

//         ]);
//         $resolver->setRequired('user');  // ← Pour pré-remplir
//     }
// }
