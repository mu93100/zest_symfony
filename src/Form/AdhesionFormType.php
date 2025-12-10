<?php

namespace App\Form;

use App\Entity\Adhesion;
use App\Entity\User;
use App\Entity\Groupe;
use App\Entity\Saison;
use App\Entity\MontantAdhesion;
use App\Entity\Motivation;
use App\Entity\ParticipationDispo;
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
            // Relations ManyToOne
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Adhérent·e',
                'data' => $this->security->getUser(),  // ← Auto-sélection DU user connecté
                'disabled' => true,  // ← Non modifiable
            ])
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'nom',
                'label' => 'Groupe',
                'data' => $options['user']->getGroupe(),  // ← Pré-rempli
                'required' => true,  // ← Obligatoire
            ])
            ->add('saison', EntityType::class, [
                'class' => Saison::class,
                'choice_label' => 'nom',
                'label' => 'Saison',
                'placeholder' => 'Choisir une saison',
            ])
            ->add('montantAdhesion', EntityType::class, [
                'class' => MontantAdhesion::class,
                'choice_label' => 'label',
                'label' => 'Montant de l’adhésion',
                'placeholder' => 'Choisir un montant',
                'required' => false,
            ])

            // Champs non mappés
            ->add('nouveauGroupe', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Créer un nouveau groupe',
            ])
            ->add('isReferent', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Je suis référent·e de mon groupe',
            ])
            ->add('isOpen', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Mon groupe peut accueillir de nouvelles adhésions',
            ])

            // ManyToMany
            ->add('motivations', EntityType::class, [
                'class' => Motivation::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('participations', EntityType::class, [
                'class' => ParticipationDispo::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('poles', EntityType::class, [
                'class' => Pole::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])

            // Textes libres
            ->add('attentesTexte', TextareaType::class, [
                'required' => false,
                'label' => 'Tes attentes spécifiques',
            ])
            ->add('competencesTexte', TextareaType::class, [
                'required' => false,
                'label' => 'Compétences à partager',
            ])

            // Consentements
            ->add('agree_fonctionnement_participation', CheckboxType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Je m’engage à respecter les règles et participer activement',
            ])
            ->add('agree_rgpd', CheckboxType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'J’accepte le traitement de mes données (RGPD)',
            ])
            ->add('agree_infos_mail', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'J’accepte de recevoir des informations par email',
            ])

            // Paiement
            ->add('paiement', CheckboxType::class, [
                'required' => false,
                'label' => 'Paiement validé (administration)',
            ]);

        // EventListener placé correctement
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $adhesion = $event->getData();
            $form = $event->getForm();

            if ($adhesion && $adhesion->getUser() && $adhesion->getUser()->isReferent()) {
                $form->add('isOpen', CheckboxType::class, [
                    'mapped' => false,
                    'label' => 'Le groupe peut accueillir de nouvelles adhésions',
                    'required' => false,
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adhesion::class,// ← LIEN ENTITÉ/FORMULAIRE pour mapper les data vers adhesion
        ]);
        $resolver->setRequired('user');  // ← Pour pré-remplir
    }
}