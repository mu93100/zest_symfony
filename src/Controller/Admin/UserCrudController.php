<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Adhesion; 
use App\Entity\Saison;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;// pour export CSV (tableau/liste )
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Repository\UserRepository;


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('roles')
                ->setLabel('Rôles')
                ->setChoices([
                    'Admin' => 'ROLE_ADMIN',
                    'Contenus' => 'ROLE_CONTENU',
                    'Adhesions' => 'ROLE_ADHESION',
                    'Produits' => 'ROLE_PRODUIT',
                    'Poles' => 'ROLE_POLE',
                    'Utilisateur' => 'ROLE_USER',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(),

            TextField::new('prenom'),
            TextField::new('nom'),
            EmailField::new('email'),
            TextField::new('telephone'),
            
            AssociationField::new('groupe', 'Groupe'),

            // "je suis référent" non modifiable dans userAdmin car user n'est pas propriétaire de la relation/ 
            // c'est groupe qui est owning side
            BooleanField::new('groupeReferent', 'Je suis référent') 
                ->onlyOnIndex()
                ->renderAsSwitch(false)
                ->formatValue(function ($value, User $user) {
                    return $user->getGroupeReferent() !== null;
                }),
            
            TextField::new('adresse'),
            TextField::new('codePostal'),
            TextField::new('ville'),
            DateField::new('dateDeNaissance'),

            IntegerField::new('nombreEnfants')
                ->setFormTypeOptions([
                    'required' => false,
                    'attr' => [
                        'min' => 0,
                        'step' => 1,
                    ]
                ]),
            IntegerField::new('compositionFoyer')
                ->setFormTypeOptions([
                    'required' => false,
                    'attr' => [
                        'min' => 1,
                        'step' => 1,
                    ]
                ]),

            TextField::new('plainPassword') // utilisation de plainPassword et non pas password car on rentre un password non hashé
                ->setLabel('Mot de passe')
                ->setFormType(PasswordType::class)
                ->onlyOnForms()
            ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User && $entityInstance->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $entityInstance, 
                $entityInstance->getPlainPassword()
            );
            $entityInstance->setPassword($hashedPassword);
            $entityInstance->setPlainPassword(null); // nettoie après hash
        }

        parent::persistEntity($entityManager, $entityInstance);
    } 

        public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Users <span style="font-weight:lighter;font-size:0.5em">[ ⚠️ impossible de modifier la.e référent.e dans Users - possible dans Groupes ]</span>');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::new('export_users', 'Exporter Users')
                ->linkToRoute('export_users')
                ->createAsGlobalAction())                

            ->add(Crud::PAGE_INDEX, Action::new('export_mails_membres', 'Exporter mails membres')
                ->linkToRoute('export_mails_membres')
                ->createAsGlobalAction())

            ->add(Crud::PAGE_INDEX, Action::new('export_mails_referents', 'Exporter mails référents')
                ->linkToRoute('export_mails_referents')
                ->createAsGlobalAction());
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void 
    { 
        if ($entityInstance instanceof User) { 
            // 1. Récupérer la saison "active" = la plus récente 
            $saisonActive = $entityManager->getRepository(Saison::class) 
                ->findOneBy([], ['dateCreation' => 'DESC']); 
            if ($saisonActive) { 
                // 2. Récupérer l’adhésion du user pour cette saison 
                $adhesion = $entityManager->getRepository(Adhesion::class) 
                    ->findOneBy([ 'user' => $entityInstance, 'saison' => $saisonActive, ]); 

            // 3. Si une adhésion existe → mettre à jour son groupe 
            if ($adhesion) { 
                $adhesion->setGroupe($entityInstance->getGroupe()); 
                $entityManager->persist($adhesion); 
            } 
        } 
    } parent::updateEntity($entityManager, $entityInstance); 
    }
}

