Ajoute cette case "Je change de groupe" avec liste déroulante dans ton AdhesionFormType :

✅ Ajoute juste après le champ groupe existant :
->add('groupe', EntityType::class, [
    'class' => Groupe::class,
    'choice_label' => 'nom',
    'label' => 'Groupe actuel',
    'placeholder' => 'Choisir un groupe',
    'required' => false,
])
// ← NOUVEAU CHAMP
->add('changeGroupe', CheckboxType::class, [
    'mapped' => false,
    'required' => false,
    'label' => '<strong>Je change de groupe</strong>',
    'attr' => ['class' => 'change-groupe-checkbox'],
])
->add('nouveauGroupe', EntityType::class, [
    'class' => Groupe::class,
    'choice_label' => 'nom',
    'label' => 'Nouveau groupe',
    'placeholder' => 'Sélectionner...',
    'required' => false,
    'attr' => ['class' => 'nouveau-groupe-select', 'style' => 'display:none;'],
])

✅ Ajoute ce JavaScript (dans ton template Twig) :
javascript
<script>
document.addEventListener('DOMContentLoaded', function() {
    const changeCheckbox = document.querySelector('.change-groupe-checkbox');
    const nouveauSelect = document.querySelector('.nouveau-groupe-select');
    
    changeCheckbox.addEventListener('change', function() {
        if (this.checked) {
            nouveauSelect.style.display = 'block';
            nouveauSelect.required = true;
        } else {
            nouveauSelect.style.display = 'none';
            nouveauSelect.required = false;
        }
    });
});
</script>
✅ Dans ton Controller (traitement) :
php
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
    $adhesion = $form->getData();
    
    // Si "Je change de groupe" coché
    if ($form->get('changeGroupe')->getData()) {
        $adhesion->setGroupe($form->get('nouveauGroupe')->getData());
    }
    
    $entityManager->persist($adhesion);
    $entityManager->flush();
}
Résultat UX :

⭕ Case "Je change de groupe" décochée → champ nouveauGroupe caché

☑️ Case cochée → liste déroulante des groupes apparaît + obligatoire

Parfait pour ton GAS ! L'adhérent garde son groupe habituel ou change facilement. 🎉