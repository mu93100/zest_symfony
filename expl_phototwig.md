DANS FICHIER TWIG
{# Photo principale #}
{% if ressource.photoPrincipale %}
    <img src="{{ asset('uploads/photos/' ~ ressource.photoPrincipale.filename) }}"
         alt="{{ ressource.photoPrincipale.description }}">
{% endif %}

{# Photos supplémentaires #}
{% for photo in ressource.photosSupp %}
    <img src="{{ asset('uploads/photos/' ~ photo.filename) }}"
         alt="{{ photo.description }}">
{% endfor %}

>> La photo principale aura son alt rempli avec sa description.
Chaque photo supplémentaire aura aussi son alt basé sur son champ description.