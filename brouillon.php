templates/security/login.html.twig :
{% extends 'base.html.twig' %}

{% block title %}Log in!{% endblock %}

{% block body %}
<form method="post">
    {% if error %}
    <div class="alert alert-danger">{{ error.messageKey|trans(error.messageData, 'security') }}</div>
    {% endif %}

    {% if app.user %}
    <div class="mb-3">
        You are logged in as {{ app.user.userIdentifier }}, <a href="{{ path('app_logout') }}">Logout</a>
    </div>
    {% endif %}

    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    <label for="username">Email</label>
    <input type="email" value="{{ last_username }}" name="_username" id="username" class="form-control" autocomplete="email" required autofocus>
    <label for="password">Mot de passe</label>
    <input type="password" name="_password" id="password" class="form-control" autocomplete="current-password" required>
    <input type="hidden" name="_csrf_token" data-controller="csrf-protection" value="{{ csrf_token('authenticate') }}">
    {# MU csrf token : token attribué à la connexion_vérifié pour requete (si c'est pas le bon token ou pas de token = NIET) supprimé à la déconnexion_SECURITE ++ #}

    {#  #}
    <div class="checkbox mb-3">
        <input type="checkbox" name="_remember_me" id="_remember_me">
        <label for="_remember_me">se rappeler de moi</label>
    </div>


    <button class="btn btn-lg btn-primary" type="submit">se connecter</button>
</form>
{% endblock %}


<div class="modal-overlay">
    <div class="modal-box">

        {% if error %}
        <div class="modal-error">{{ errorMessage }}</div>
        {% endif %}

        <h2>{{ title }}</h2>

        <div class="modal-content">
            {# Ici tu mets ton formulaire ou ton contenu #}
            {{ block('modal_body') }}
        </div>

    </div>
</div>

{% extends 'base.html.twig' %}

{% block title %}Connexion{% endblock %}

{% block body %}
<div class="modal-overlay">
    <div class="modal-box">

        {% if error %}
        <div class="modal-error">[ email ou mot de passe incorrect ]</div>
        {% endif %}

        <h2>connexion</h2>
        <img src="/images/logo.png" alt="logo zest" class="logo">

        {% block modal_body %}
        <form action="{{ path('app_login') }}" method="post">

            <div class="form-field">
                <label for="inputEmail">Email</label>
                <input type="email" id="inputEmail" name="_username" value="{{ last_username }}" required>
            </div>

            <div class="form-field">
                <label for="inputPassword">Mot de passe</label>
                <div class="password-wrapper">
                    <input type="password" id="inputPassword" name="_password" required>
                    <button type="button" class="password-toggle" onclick="togglePasswordVisibility('inputPassword', this)">👁</button>
                </div>
            </div>

            <input type="hidden" name="_csrf_token" value="{{ csrf_token('authenticate') }}">

            <button type="submit" class="form-submit">se connecter</button>

            <div class="form-links">
                <a href="{{ path('app_forgot_password_request') }}">mot de passe oublié</a>
            </div>
            <li><a href="https://www.socleo.com/demandez-une-demo/">commander</a></li>

        </form>
        {% endblock %}

    </div>
</div>
{% endblock %}