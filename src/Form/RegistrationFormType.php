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



class RegistrationFormType extends AbstractType
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   //CLAUDE MISE EN COMMENTAIRE MARDI 16:50
        // $groupes = $this->em->getRepository(Groupe::class)->findAll();

        // // On construit le tableau des choix : "Nouveau groupe" en premier
        // $listeGroupes = ['Nouveau groupe' => 'nouveau'];
        // foreach ($groupes as $g) {
        //     $listeGroupes[$g->getNom()] = $g->getId();
        // }

        $builder
            ->add('prenom', null, [
                'label' => 'Prénom',
                'required' => true
            ])
            ->add('nom', null, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('email', null, [
                'label' => 'Email',
                'required' => true
            ])
            ->add('telephone', null, [
                'label' => 'Numéro de téléphone',
                'required' => true
            ])
            ->add('adresse', null, [
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('codePostal', null, [
                'label' => 'Code postal',
                'required' => false
            ])
            ->add('ville', null, [
                'label' => 'Ville',
                'required' => true
            ])
            ->add('dateDeNaissance', null, [
                'label' => 'Date de naissance',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('compositionFoyer', IntegerType::class, [
                'label' => 'Composition du foyer',
                'property_path' => 'compositionFoyer',
                'required' => false,
                'attr' => ['min' => 1, 'step' => 1],
            ])
            ->add('nombreEnfants', IntegerType::class, [
                'label' => 'Nombre d\'enfants -12 ans',
                'property_path' => 'nombreEnfants',
                'required' => false,
                'attr' => ['min' => 0, 'step' => 1],
            ])

            // Liste déroulante des groupes existants
            ->add('groupe', EntityType::class, [
                'label' => 'Nom de mon groupe',
                'class' => Groupe::class,
                'choice_label' => 'nom',
                'required' => false, // ← Important !
                'placeholder' => 'Choisir un groupe existant',
                // A SUPPRIMER 'attr' => ['id' => 'groupe-liste'], MERCREDI / VOIR A QUOI SERT LE id groupe-liste
            ])
            // Champ texte pour nouveau groupe            
            ->add('nouveauGroupe', TextType::class, [
                'mapped' => false, // champ libre, pas lié directement à User
                'required' => false,
                'label' => 'Je crée un nouveau groupe',
                'attr' => [
                // A SUPPRIMER 'id' => 'nouveau-groupe-field',  MERCREDI / VOIR A QUOI SERT LE id
                'placeholder' => 'Nom du nouveau groupe'
                ],
            ])
            ->add('isReferent', CheckboxType::class, [
                'label' => "Je suis référent·e de mon groupe",
                'required' => false,
            ])
            ->add('plainPassword', PasswordType::class, [
                // TOUJOURS = password est lu et encodé dans le controller et non dans entity
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
            ])

            // contraintes IsTrue -> rend les cases obligatoires à cocher / 'mapped' => false -> pas de données stockées dans entité
            ->add('agree_fonctionnement', CheckboxType::class, [
                'mapped' => false,
                'label' => "Je m'engage à respecter les règles de fonctionnement du GAS et à participer activement [ documents à lire : statuts - RI - charte ]",
                'constraints' => [
                    new IsTrue([
                        'message' => '[M E R C I  de valider les mentions légales]',
                    ]),
                ],
            ])
            ->add('agree_rgpd', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte que mes données personnelles soient utilisées à des fins statistiques et logistiques dans le cadre du fonctionnement du GAS [ document à lire : Mentions légales - RGPD ]",
                'constraints' => [
                    new IsTrue([
                        'message' => "[M E R C I  de valider l'utilisation des données personnelles | dans le cas contraire : veuillez contacter le CA ca@cortozest.org]",
                    ]),
                ],
            ])
            ->add('agree_infos_mail', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte de recevoir des informations par email sur les activités du GAS",
                'constraints' => [
                    new IsTrue([
                        'message' => '[I M P O S S I B L E  de fonctionner autrement]',
                    ]),
                ],
            ]);

        //CLAUDE MISE EN COMMENTAIRE MARDI 16:50
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $user = $event->getData();
            // fin claude
        });
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
