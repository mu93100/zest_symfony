<?php

namespace App\Validator\Constraints;

use App\Entity\Adhesion;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\FormInterface;

class GroupeObligatoireValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /** @var FormInterface $form */
        $form = $this->context->getRoot();

        $selectedExisting = null;
        $newGroupName = null;

        // Cas RegistrationFormType : champ "groupe"
        if ($form->has('groupe')) {
            $selectedExisting = $form->get('groupe')->getData();
        }

        // Cas AdhesionFormType : champ "changeGroupe"
        if ($form->has('changeGroupe')) {
            $selectedExisting = $selectedExisting ?? $form->get('changeGroupe')->getData();
        }

        // Champ commun "nouveauGroupe"
        if ($form->has('nouveauGroupe')) {
            $newGroupName = $form->get('nouveauGroupe')->getData();
        }

        // 👉 Nouveau test : si l’adhésion a déjà un groupe (pré-rempli avec celui du user)
        $groupeDejaPresent = $value instanceof Adhesion ? $value->getGroupe() : null;

        // Validation : au moins un des trois doit être rempli
        if (!$selectedExisting && !$newGroupName && !$groupeDejaPresent) {
            // Choisir le champ sur lequel afficher l’erreur
            $errorPath = null;
            if ($form->has('changeGroupe')) {
                $errorPath = 'changeGroupe';
            } elseif ($form->has('groupe')) {
                $errorPath = 'groupe';
            } elseif ($form->has('nouveauGroupe')) {
                $errorPath = 'nouveauGroupe';
            }

            $builder = $this->context->buildViolation($constraint->message);
            if ($errorPath) {
                $builder->atPath($errorPath);
            }
            $builder->addViolation();
        }
    }
}
