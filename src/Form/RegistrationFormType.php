<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


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
            ->add('date_de_naissance')
            ->add('composition_foyer', IntegerType::class, [
                'property_path' => 'composition_foyer', 
                'required' => false,
                'attr' => ['min' => 1, 'step' => 1],
                ])
            ->add('nombreenfants', IntegerType::class, [
                'property_path' => 'nombreenfants', 
                'required' => false,
                'attr' => ['min' => 0, 'step' => 1],
                ])
            // ->add('groupe')
            ->add('is_referent')

            ->add('plainPassword', PasswordType::class, [
                // TOUJOURS = password est lu et encodé dans le controller et non dans entity
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'M E R C I  de renseigner ton mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'M I N I M U M  {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            
            ->add('agree_fonctionnement_participation', CheckboxType::class, [
                'mapped' => false,
                'label' => "Je m'engage à respecter les règles de fonctionnement du GAS et à participer activement * [ documents à lire : statuts - RI - charte ]",
                'constraints' => [
                    new IsTrue([
                        'message' => 'M E R C I  de valider les mentions légales',  
                    ]),
                ],
            ])    
            ->add('agree_rgpd', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte que mes données personnelles soient utilisées à des fins statistiques et logistiques dans le cadre du fonctionnement du GAS * [ documents à lire : Mentions légales - RGPD ]",
                'constraints' => [
                    new IsTrue([
                        'message' => 'M E R C I  de valider les mentions légales',
                    ]),
                ],
            ])
            ->add('agree_infos_mail', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte de recevoir des informations par email sur les activités du GAS *",
                'constraints' => [
                    new IsTrue([
                        'message' => 'M E R C I  de valider les mentions légales',
                    ]),
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
