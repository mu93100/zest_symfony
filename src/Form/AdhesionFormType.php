<?php

namespace App\Form;

use App\Entity\Adhesion;
use App\Entity\Groupe;
use App\Entity\Motivation;
use App\Entity\ParticipationDispo;
use App\Entity\Pole;
use App\Entity\User;
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
            ->add('email')
            //groupe : liste déroulante (avec EntityType) + champ "nouveau groupe"
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'nom',
                'placeholder' => 'Nouveau groupe',
                'required' => false,
            ])
            ->add('nouveau_groupe', TextType::class, [
                'mapped' => false, // champ libre, pas lié directement à User
                'label' => 'Nom du nouveau groupe',
                'required' => false,
            ])
            ->add('isReferent', CheckboxType::class, [
                'label' => 'Je suis référent.e de mon groupe',
                'required' => false,
            ])
            ->add('isOpen', CheckboxType::class, [
                'label' => 'Mon groupe peut accueillir de nouveaux adhérents',
                'required' => false,
            ])
            ->add('motivation', EntityType::class, [
                'class' => Motivation::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => true, // cases à cocher
            ])
            ->add('attentes', TextareaType::class, [
                'label' => 'Décris tes attentes spécifiques',
                'required' => false,
            ])
            ->add('participationDispo', EntityType::class, [
                'class' => ParticipationDispo::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true, // cases à cocher
            ])
            ->add('pole', EntityType::class, [
                'class' => Pole::class,
                'choice_label' => 'nom',
                'placeholder' => 'Pôle(s) de travail auquel(s) je souhaite participer',
            ])
            ->add('competences', TextareaType::class, [
                'label' => 'Quelles compétences particulières peux-tu partager ?',
                'required' => false,
            ])

            ->add('engagement', CheckboxType::class, [
                'label' => 'Je m\'engage à respecter les règles du GAS',
                'required' => true,
            ])
            ->add('paiement', CheckboxType::class, [
                'label' => 'Je règle mon adhésion annuelle par virement',
                'required' => true,
            ])
            ->add('adhesion', EntityType::class, [
                'class' => Adhesion::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une adhésion',
            ])
            ->add('newsletter', CheckboxType::class, [
                'label' => 'J\'accepte de recevoir des informations par email',
                'required' => false,
            ]);
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $user = $event->getData();

            // si isReferent = true -> on ajoute le champ isOpen du groupe
            if ($user && $user->isReferent()) {
                $form->add('isOpen', CheckboxType::class, [
                    'mapped' => false, // car isOpen est dans Groupe, pas User
                    'label' => 'Le groupe peut accueillir de nouvelleaux adhérent.es',
                    'required' => false,
                ]);
            }
        });
    }
    




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
