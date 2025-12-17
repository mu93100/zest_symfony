<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

//  GroupeObligatoire()-> contrainte personnalisée symfony pour groupe OBLIGATOIRE car à utiliser dans plusieurs formulaires 
// ],                           // avec le use App\Validator\Constraints\GroupeObligatoire;

/**
 * @Annotation
 */
class GroupeObligatoire extends Constraint
{
    public $message = "[ E R R O R  tu dois être rattaché à un groupe existant ou en créer un nouveau, si tu n'es dans aucun groupe : contacte le pôle adhésion : adhesion@corto-zest.org ]";
}
