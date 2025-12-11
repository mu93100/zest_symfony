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

            // le groupe est lié au user et les champs non modifiables ne sont pas obligés
            // d'être dans le formType UNIQUEMENT LES CHAMPS DE FORMULAIRE MODIFIABLE PAR LE USER
            // ON APPELLE JUSTE DANS LE twig
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'email',
            //     'label' => 'Adhérent·e',
            //     'data' => $options['user'],   // ← pré-rempli avec l’utilisateur connecté
            //     'disabled' => true,  // ← affiché MAIS non modifiable
            // ])
            // ->add('groupe', EntityType::class, [
            //     'class' => Groupe::class,
            //     'choice_label' => 'nom',
            //     'label' => 'Groupe',
            //     'data' => $options['user']->getGroupe(),  // ← Pré-rempli avec le groupe du user
            //     'required' => true,  // ← Obligatoire
            // ])
            
            // je change de groupe
            ->add('changeGroupe', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => '<strong>Je change de groupe</strong>',
                'attr' => ['class' => 'change-groupe-checkbox'],
            ])
            // Je crée un nouveau groupe 
            ->add('nouveauGroupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'nom',
                'label' => 'Je crée un nouveau groupe',
                'placeholder' => 'nom du nouveau groupe',
                'required' => false,
                'attr' => ['class' => 'nouveau-groupe-select', 'style' => 'display:none;'],
            ])


            ->add('montantAdhesion', EntityType::class, [
                'class' => MontantAdhesion::class,
                'choice_label' => 'label',
                'label' => 'Montant de l’adhésion',
                'placeholder' => 'Choisir un montant',
                'required' => true,
            ])

            // Champs non mappés
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
            ->add('dispos', EntityType::class, [
                'class' => Dispo::class,
                'choice_label' => 'label', 
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('poles', EntityType::class, [
                'class' => Pole::class,
                'choice_label' => 'nom',// pas de label!
                'placeholder' => 'Pôle(s) de travail auquel(s) je souhaite participer',
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
            ->add('agree_fonctionnement', CheckboxType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Je m’engage à respecter les règles et participer activement',
            ])

            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'nom',
                'placeholder' => 'mon groupe',
                'required' => false,
            ])

            // Paiement  JUSTE POUR admin
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