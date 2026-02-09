<?php

namespace App\Form;

use App\Entity\Adhesion;
use App\Entity\Groupe;
use App\Entity\MontantAdhesion;
use App\Entity\Motivation;
use App\Entity\Dispo;
use App\Entity\Pole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Validator\Constraints\GroupeObligatoire;



class AdhesionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // AdhesionFormType::buildForm
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => false,
                'attr' => ['style' => 'display:none'], // caché
                'data' => $options['user']->getGroupe(), // groupe pré rempli
            ]) // Important: ce champ DOIT être mappé (par défaut mappé = true) pour hydrater l’entite

            // G R O U P E 
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
                'constraints' => [
                    new UniqueEntity([
                        'entityClass' => Groupe::class,
                        'fields' => 'nom',
                        'message' => '[ E R R O R   ce nom de groupe existe déjà ]'
                    ])
                ],
            ])
            ->add('adresseDistribNouveau', TextType::class, [
                'mapped' => false,
                'label' => 'Adresse de distribution du nouveau groupe',
                'required' => false,
            ])
            ->add('codePostalNouveau', TextType::class, [
                'mapped' => false,
                'label' => 'Code postal du nouveau groupe',
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
                'label' => 'Je suis référent·e de mon groupe pour la saison en cours',
                'mapped' => false,
                'required' => false,
            ])
            // Groupe ouvert
            ->add('isOpen', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Mon groupe peut accueillir de nouveaux adhérent·es',
            ])
            // Champs pour MODIFIER groupe existant 
            // Adresse distribution 
            ->add('adresseDistrib', TextType::class, [
                'label' => "Modifier l'adresse de distribution du groupe",
                'required' => false,
                'mapped' => false,
                'attr' => ['placeholder' => 'Lieu de distribution des commandes'],
            ])
            ->add('codePostal', TextType::class, [
                'label' => "Code postal",
                'required' => false,
                'mapped' => false,
                'attr' => ['placeholder' => '93100'],
            ])
            // Ville
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => false,
                'mapped' => false,
            ])
            // fin   G R O U P E 
            // Motivations
            ->add('motivations', EntityType::class, [
                'class' => Motivation::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'Quelles sont tes principales motivations pour participer au GAS ?',
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
            ->add('paiementValide', CheckboxType::class, [
                'required' => false,
                'label' => 'Paiement validé (JUSTE POUR admin)',
                'attr' => ['style' => 'display:none;'],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adhesion::class,
            'constraints' => [new GroupeObligatoire()],
        ]);
        $resolver->setRequired('user');
    }
}