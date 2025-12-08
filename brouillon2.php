<?php
    // Infos du référent (calculées via User)
    TextField::new('referentNom')
    ->formatValue(function ($value, $entity) {
    return $entity->getReferent()?->getNom() ?? '—';
    })
    ->onlyOnDetail(),

    TextField::new('referentEmail')
    ->formatValue(function ($value, $entity) {
    return $entity->getReferent()?->getEmail() ?? '—';
    })
    ->onlyOnDetail(),

    TextField::new('referentTelephone')
    ->formatValue(function ($value, $entity) {
    return $entity->getReferent()?->getTelephone() ?? '—';
    })
    ->onlyOnDetail(),