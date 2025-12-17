 **COOKIES**
 {# Bandeau cookies #}
            {% block cookies %}
                <section id="cookieBanner" class="cookie-banner hide">
                    <p>Ce site utilise des cookies pour améliorer votre expérience.</p>
                    <button id="acceptCookies" class="btn btn-success">Accepter</button>
                    <button id="refuseCookies" class="btn btn-danger">Refuser</button>
                </section>
            {% endblock %}
 
 
  // -------------------------------
  // Bandeau cookies
  // -------------------------------
  const banner = document.getElementById('cookieBanner');
  const acceptBtn = document.getElementById('acceptCookies');
  const refuseBtn = document.getElementById('refuseCookies');
  const resetBtn = document.getElementById('resetCookies'); // Bouton optionnel pour réinitialiser
 
  // Vérifie si un choix existe déjà
  const choice = localStorage.getItem('cookiesChoice');
 
  if (!choice) {
    // Aucun choix → affiche le bandeau
    banner.classList.remove('hide');
  } else {
    // Un choix existe → cache le bandeau
    banner.classList.add('hide');
    console.log('Choix des cookies déjà enregistré :', choice);
  }
 
  // Quand l’utilisateur clique sur "Accepter"
  acceptBtn.onclick = function() {
    localStorage.setItem('cookiesChoice', 'accepted');
    banner.classList.add('hide');
    console.log('Cookies acceptés.');
  };
 
  // Quand l’utilisateur clique sur "Refuser"
  refuseBtn.onclick = function() {
    localStorage.setItem('cookiesChoice', 'refused');
    banner.classList.add('hide');
    console.log('Cookies refusés.');
  };
 
 
**Le plus simple : SEO dans base.html.twig**
Tu mets des blocs SEO dans ton layout global :
templates/base.html.twig
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>
        {% block title %}
            Mon site – Titre par défaut
        {% endblock %}
    </title>
    <meta name="description" content="{% block meta_description %}
        Description par défaut de mon site.
    {% endblock %}">
    {# Exemple : keywords si tu veux #}
    <meta name="keywords" content="{% block meta_keywords %}
        symfony, site web
    {% endblock %}">
</head>
<body>
    {% block body %}{% endblock %}
</body>
</html>

✅ 2. **Utilisation dans la page d’accueil**
Tu surcharges juste les blocs que tu veux changer.
templates/home/index.html.twig
{% extends 'base.html.twig' %}
{% block title %}
    Accueil – Bienvenue sur mon site
{% endblock %}
{% block meta_description %}
    Ceci est la page d’accueil de mon site Symfony.
{% endblock %}
{% block meta_keywords %}
    accueil, symfony, site
{% endblock %}
{% block body %}
<h1>Page d'accueil</h1>
{% endblock %}

✅ 3. **Utilisation dans n’importe quelle autre page**
Toujours le même principe.
templates/contact/index.html.twig
{% extends 'base.html.twig' %}
{% block title %}
    Contact – Mon site
{% endblock %}
{% block meta_description %}
    Venez nous contacter via ce formulaire.
{% endblock %}
{% block meta_keywords %}
    contact, formulaire, symfony
{% endblock %}
{% block body %}
<h1>Contact</h1>
{% endblock %}
 
 

 