**RAJOUTER** 
use Symfony\Component\Validator\Constraints as Assert;

**puis après #[ORM\Column]**
[ORM\Column(length: 10)]
#[Assert\NotBlank]
#[Assert\Regex(pattern: '/^\d{10}$/', message: 'Téléphone : exactement 10 chiffres.')]
private ?**string**$telephone = null;

**ATTENTION / les chiffres doivent être en string pour taille MAX**
#[Assert\NotBlank]
#[Assert\Regex(pattern: '/^\d{5}$/', message: 'Code postal : exactement 5 chiffres.')]
private ?string $codePostal = null;

--------------------------------------------
**pour résultat (int) sup à 0**
dans ...FormType
**RAJOUTER** use Symfony\Component\Form\Extension\Core\Type\IntegerType;
            ->add('composition_foyer', IntegerType::class, [
                'property_path' => 'composition_foyer',
                'required' => false, **=== PAS OBLIGATOIRE**
                'attr' => ['min' => 1, 'step' => 1],
                ])

dans ...php (entity)
#[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: false)]
#[Assert\NotNull(message: 'La composition du foyer est requise.')]
#[Assert\GreaterThanOrEqual(1, message: 'La composition du foyer doit être au moins 1.')]
private ?int $composition_foyer = null;                
-------------------------------------------------

**“mapper” veut dire relier les champs du formulaire aux propriétés de ton objet (entité).**
Formulaire mappé
Quand un champ est mappé (par défaut) :

Le nom du champ (telephone, nombreEnfants, etc.) correspond à une propriété de ton entity User.

Quand tu fais handleRequest() puis isSubmitted() + isValid(), Symfony remplit automatiquement l’objet avec les valeurs du formulaire, puis tu peux faire persist() / flush() et ça part en base.

Champ non mappé ('mapped' => false)
Quand tu mets l’option 'mapped' => false sur un champ :

Le champ n’est pas relié à une propriété de l’entité.

Symfony ne l’écrit pas dans l’objet lors du handleRequest().

Tu dois le lire toi‑même dans le contrôleur ($form->get('plainPassword')->getData(), par exemple) et décider quoi en faire (hasher le mot de passe, envoyer un mail, etc.).

Donc :

“mappé” = champ lié à l’entité (rempli / sauvegardé automatiquement).

“non mappé” = champ utilisé juste pour la logique du formulaire, pas stocké directement dans l’entité.