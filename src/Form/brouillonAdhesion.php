

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
                'expanded' => false, // liste déroulante avec 1 seul choix possible
                'placeholder' => 'Pôle(s) de travail auquel(s) je souhaite participer', 
            ])
            // 🔹 Adhesion : liste déroulante
            ->add('adhesion', EntityType::class, [
                'class' => Adhesion::class,
                'choice_label' => 'libelle',
                'multiple' => false,
                'expanded' => false, // liste déroulante avec 1 seul choix possible
                'placeholder' => 'montant de mon adhésion',
            ])

            // 🔹 Participation dispo : cases à cocher
            ->add('participationDispo', EntityType::class, [
                'class' => ParticipationDispo::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true, // cases à cocher
            ])
            // 🔹 groupe                        : liste déroulante
            ->add('groupe', EntityType::class, [
                'class' => Adhesion::groupe,
                'choice_label' => 'nom',
                'multiple' => false,
                'expanded' => false, // liste déroulante avec 1 seul choix possible
                'placeholder' => ' ',
            ])