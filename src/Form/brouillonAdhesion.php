

            // 🔹 Motivation : cases à cocher
            ->add('motivation', EntityType::class, [
                'class' => Motivation::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => true, // cases à cocher
            ])

            // 🔹 Pole : liste déroulante
            ->add('pole', EntityType::class, [
                'class' => Pole::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'expanded' => false, // liste déroulante
            ])

            // 🔹 Adhesion : liste déroulante
            ->add('adhesion', EntityType::class, [
                'class' => Adhesion::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une adhésion',
            ])

            // 🔹 Participation dispo : cases à cocher
            ->add('participationDispo', EntityType::class, [
                'class' => ParticipationDispo::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true, // cases à cocher
            ])