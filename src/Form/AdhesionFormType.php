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


class AdhesionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'nom',
                'placeholder' => 'nouveau grOOOOoupe',
                'required' => false,
                'attr' => ['style' => 'display:none;'],
            ])        
            // je change de groupe
            ->add('changeGroupe', CheckboxType::class, [
                'mapped' => false, // pas mappé sur adhesion
                'required' => false,
                'label' => 'Je changehhh de groupe',
                'attr' => ['class' => 'change-groupe-checkbox'],
            ])
            // Je crée un nouveau groupe/ TextType pas EntityType car pas de champ pour nouveau groupe
            ->add('nouveauGroupe', TextType::class, [
                'mapped' => false, // pas mappé sur adhesion
                'label' => 'Je crée un nouveau groupe',
                'required' => false,
                'attr' => ['class' => 'nom du nouveau groupe'],
                // 'attr' => ['class' => 'nom du nouveau groupe', 'style' => 'display:none;'],
            ])
            ->add('montantAdhesion', EntityType::class, [
                'class' => MontantAdhesion::class,
                'choice_label' => 'libelle',
                'label' => 'Montant de l’adhésion',
                'placeholder' => 'Choisir un montant',
                'required' => true,
            ])



            
            ->add('isReferent', CheckboxType::class, [
                'label' => 'Je suis référent·e de mon groupe',
                'mapped' => false, // pas mappé sur adhesion
                'required' => false,
            ])
            
            ->add('isOpen', CheckboxType::class, [
                'mapped' => false,// pas mappé sur adhesion
                'required' => false,
                'label' => 'Mon groupe peut accueillir de nouvelleaux adhérent.es',
                'attr' => ['class' => 'is-open-checkbox'],
                // 'attr' => ['class' => 'is-open-checkbox', 'style' => 'display:none;'],
            ])

            ->add('adresseDistribution', TextType::class, [
                'label' => 'Adresse de distribution du groupe',
                'required' => false,
                'mapped' => false, // pas mappé sur adhesion
                'attr' => [
                    'placeholder' => 'Lieu de distribution des commandes',
                    'class' => 'referent-field',
                    'style' => 'display:none;'
                ]
            ])

            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => false,
                'mapped' => false, // pas mappé sur adhesion
                'attr' => ['class' => 'referent-field']
            ])

            // ManyToMany
            ->add('motivations', EntityType::class, [
                'class' => Motivation::class,
                'choice_label' => 'libelle',   // le texte affiché à côté de chaque case
                'multiple' => true,          // plusieurs choix possibles
                'expanded' => true,          // affiché en checkboxes (et pas en <select>)
                'required' => false,
            ])

            // Texte libre
            ->add('attentesTexte', TextareaType::class, [
                'required' => false,
                'label' => 'Tes attentes spécifiques',
            ])

            ->add('dispos', EntityType::class, [
                'class' => Dispo::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])

            ->add('competencesTexte', TextareaType::class, [
                'required' => false,
                'label' => 'Compétences à partager',
            ])

            ->add('poles', EntityType::class, [
                'class' => Pole::class,
                'choice_label' => 'nom', // pas de label!
                'placeholder' => 'Pôle(s) de travail auquel(s) je souhaite participer',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])

            // Consentement requis mais pas mappé/enregistré
            ->add('agree_fonctionnement', CheckboxType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Je m’engage à respecter les règles et participer activement',
            ])

            ->add('montantAdhesion', EntityType::class, [
                'class' => MontantAdhesion::class,
                'choice_label' => 'libelle',
                'label' => 'Montant de l’adhésion',
                'placeholder' => 'Choisir un montant',
                'required' => true,
            ])
            // Paiement  JUSTE POUR admin
            ->add('paiement', CheckboxType::class, [
                'required' => false,
                'label' => 'Paiement validé (JUSTE POUR admin)',
            ]);

        // EventListener placé correctement
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $adhesion = $event->getData();
            $form = $event->getForm();
            
// SUREMENT A ENLEVER
            // if ($adhesion && $adhesion->getUser() && $adhesion->getUser()->isReferent()) {
            //     $form->add('isOpen', CheckboxType::class, [
            //         'mapped' => false,
            //         'label' => 'Le groupe peut accueillir de nouvelles adhésions',
            //         'required' => false,
            //     ]);
            // }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adhesion::class, // ← LIEN ENTITÉ/FORMULAIRE pour mapper les data vers adhesion
        ]);
        $resolver->setRequired('user');  // ← Pour pré-remplir
    }
}
