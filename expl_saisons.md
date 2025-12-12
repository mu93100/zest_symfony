**pour dashboardAdmin avec affichage par défaut saison actuelle et possibilité choix autre saison**:

**création fichier SaisonExtension**
**templates/admin/layout.html.twig**
{% extends '@EasyAdmin/page/content.html.twig' %}

{% block content_header %}
    {{ parent() }}

    {% if saisons is not empty %}
        <form method="get" action="{{ app.request.uri }}" style="margin-top:1rem;">
            <label for="saison">Saison :</label>
            <select id="saison" name="saison" onchange="this.form.submit()">
                {% for saison in saisons %}
                    <option value="{{ saison.id }}"
                        {% if saisonEnCours and saison.id == saisonEnCours.id %}selected{% endif %}>
                        {{ saison.nom }}
                    </option>
                {% endfor %}
            </select>
        </form>
    {% else %}
        <p style="margin-top:1rem;">Aucune saison trouvée.</p>
    {% endif %}
{% endblock %}


**créer src/Twig/SaisonExtension.php**
<?php

namespace App\Twig;

use App\Repository\SaisonRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class SaisonExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private SaisonRepository $saisonRepository,
        private RequestStack $requestStack
    ) {}

    public function getGlobals(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $saisonId = $request?->query->get('saison');

        $saisonEnCours = $saisonId
            ? $this->saisonRepository->find($saisonId)
            : $this->saisonRepository->findOneBy([], ['dateCreation' => 'DESC']);

        return [
            'saisons' => $this->saisonRepository->findAll(),
            'saisonEnCours' => $saisonEnCours,
        ];
    }
}

**Déclare le service dans config/services.yaml**
 App\Twig\SaisonExtension:
        arguments:
            - '@App\Repository\SaisonRepository'
            - '@request_stack'
        tags: ['twig.extension']
