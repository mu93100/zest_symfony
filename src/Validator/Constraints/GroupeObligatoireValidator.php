<?php

namespace App\Validator\Constraints;

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

        // Validation : au moins un des deux doit être rempli
        if (!$selectedExisting && !$newGroupName) {
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
