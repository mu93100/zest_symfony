{# REQUEST twig
    
{% block body %}
    <div class="container">
        <div class="reset-password-modal">
            <h1>réinitialiser mon mot de passe</h1>
            {{ form_start(requestForm) }}
                <div class="form-group">
                    {{ form_label(requestForm.email, 'Email') }}
                    {{ form_widget(requestForm.email, {'attr': {'class': 'form-control'}}) }}
                    {{ form_errors(requestForm.email) }}
                </div>

                <button type="submit" class="form-submit">
                    Envoyer le lien de réinitialisation
                </button>
            {{ form_end(requestForm) }}
        </div>
    </div>
{% endblock %}#}

{#   {% extends 'base.html.twig' %}  <!-- ou modal_base.html.twig -->.  #}
{% extends 'modal_base.html.twig' %}

{% block title %}mot de passe oublié{% endblock %}

{% block modal_title %}mot de passe oublié{% endblock %}

{% block modal_body %}
{{ form_start(requestForm) }}
<div class="form-field">
    <label for="inputEmail">Email</label>
    <input
        type="email"
        id="inputEmail"
        name="{{ requestForm.email.vars.full_name }}"
        required
        autocomplete="email"
        class="form-input">
    {{ form_errors(requestForm.email) }}
</div>

<button type="submit" class="form-submit submit-full">envoyer le lien de réinitialisation</button>
{{ form_end(requestForm, {'render_rest': false}) }}
{% endblock %}