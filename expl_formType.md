**ex pour champs /Pté à choix multiple**
  // Champ texte pour les tailles disponibles
           ->add('tailles', ChoiceType::class, [
                'choices' => [
                    'Tailles adultes' => [
                        'XS' => 'XS',
                        'S' => 'S',
                        'M' => 'M',
                        'L' => 'L',
                        'XL' => 'XL',
                        'XXL' => 'XXL',
                        'Taille unique' => 'Taille unique'
                    ],
                    'Tailles enfants' => [
                        '2 ans' => '2 ans',
                        '3 ans' => '3 ans',
                        '4 ans' => '4 ans',
                        '5 ans' => '5 ans',
                        '6 ans' => '6 ans',
                        '7 ans' => '7 ans',
                        '8 ans' => '8 ans',
                        '9 ans' => '9 ans',
                        '10 ans' => '10 ans',
                        '11 ans' => '11 ans',
                        '12 ans' => '12 ans',
                        '13 ans' => '13 ans',
                        '14 ans' => '14 ans',
                        '15 ans' => '15 ans',
                        '16 ans' => '16 ans'
                    ]
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Tailles disponibles'
            ])



**pour mettre * sur tous les champs requis**  
'required' => true dans RegistrationFormType          
-> Créer doss et fichier : templates/form/form_theme.html.twig
{% block form_label %}
    {% if label is not same as(false) %}
        {% set labelText = label|default(name)|trans %}
        {% if required %}
            {{ labelText }} *
        {% else %}
            {{ labelText }}
        {% endif %}
    {% endif %}
{% endblock %}

-> déclarer le thème dans twig.yaml
Dans config/packages/twig.yaml, ajoute ton thème à twig:
twig:
    form_themes:
        - 'form/form_theme.html.twig'

        
