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


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("prenom")
            ->add("nom")
            ->add('email')
            ->add('telephone')
            ->add('adresse')
            ->add('code_postal')
            ->add('ville')
            ->add('date_de_naissance')
            ->add('composition_foyer', IntegerType::class, [
                'property_path' => 'composition_foyer', 
                'required' => false,
                'attr' => ['min' => 1, 'step' => 1],
                ])
            ->add('nombre_enfants', IntegerType::class, [
                'property_path' => 'nombre_enfants', 
                'required' => false,
                'attr' => ['min' => 0, 'step' => 1],
                ])

            //groupe : liste déroulante (avec EntityType) + champ "nouveau groupe"
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'nom',
                'placeholder' => 'Nouveau groupe',
                'required' => false,
            ])
            ->add('nouveau_groupe', TextType::class, [
                'mapped' => false, // champ libre, pas lié directement à User
                'required' => false,
                'label' => 'Nom du nouveau groupe',
            ])
            //si isReferent = true -> case + champ "isOpen" dans entity groupe (voir plus bas)
            ->add('isReferent', CheckboxType::class, [
                'required' => false,
                'label' => 'Je suis référent.e de mon groupe',
            ])

            ->add('plainPassword', PasswordType::class, [
                // TOUJOURS = password est lu et encodé dans le controller et non dans entity
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
            ])
            // contraintes IsTrue -> rend les cases obligatoires à cocher / 'mapped' => false -> pas de données stockées dans entité
            ->add('agree_fonctionnement_participation', CheckboxType::class, [
                'mapped' => false,
                'label' => "Je m'engage à respecter les règles de fonctionnement du GAS et à participer activement * [ documents à lire : statuts - RI - charte ]",
                'constraints' => [
                    new IsTrue([
                        'message' => '[M E R C I  de valider les mentions légales]',  
                    ]),
                ],
            ])    
            ->add('agree_rgpd', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte que mes données personnelles soient utilisées à des fins statistiques et logistiques dans le cadre du fonctionnement du GAS * [ document à lire : Mentions légales - RGPD ]",
                'constraints' => [
                    new IsTrue([
                        'message' => "[M E R C I  de valider l'utilisation des données personnelles | dans le cas contraire : veuillez contacter le CA ca@cortozest.org]",
                    ]),
                ],
            ])
            ->add('agree_infos_mail', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte de recevoir des informations par email sur les activités du GAS *",
                'constraints' => [
                    new IsTrue([
                        'message' => '[I M P O S S I B L E  de fonctionner autrement]',
                    ]),
                ],
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
