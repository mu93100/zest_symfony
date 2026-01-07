<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de la recette *',
                'attr' => ['placeholder' => 'ex: Risotto cédrat burrata']
            ])
            ->add('nombreMangeurs', IntegerType::class, [
                'label' => 'Nombre de mangeurs *'
            ])
            // src/Form/RecetteFormType.php

            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true, // checkbox
                'label' => 'Produits utilisés *',
                'attr' => [
                    'class' => 'form-produits-utilises'
                ],
            ])
            ->add('ingredients', TextareaType::class, [
                'label' => 'Ingrédients *',
                'attr' => ['placeholder' => 'ex: 3 citrons bergamote, 80g sucre, 100g farine...']
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description de la recette    [ Le texte sera publié à l' identique ]",
                'attr' => ['placeholder' => 'soit drôle sérieux.se créatif.ve vénère militant.e .. [ on adorera tous les tons, exceptés les contenus haineux et discriminatoires ]']
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de la recette',
                'mapped' => false,
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'ajouter la recette',
                'attr' => ['class' => 'form-submit']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
