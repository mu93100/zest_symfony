.invalid-feedback {
color: #00ff9d;
font-weight: 900;
}

.is-invalid {
background-color: #00ff9d;
font-weight: 900;
}
/* ----------------------- FORMULAIRE QUESTIONNAIRE ----------------------- */
.form-container {
padding: 3rem 4.5rem 3rem 4.5rem;
}
.form-container-gd {
padding: 2rem 3rem 2rem 3rem;
}
.form-section {
margin-top: 3.5rem;
font-size: 0.8rem;
}

.form-section-mini {
margin-top: 1.1rem;
/* font-size: 0.8rem; */
}
/* --- I N P U T GRID --- */
.form-grid {
display: grid;
grid-template-columns: repeat(16, 1fr);
}

.row {
display: flex;
flex-direction: column; /* label au dessus de input */
width: 100%;
}

.row--small { grid-column: span 3;}
.row--medium { grid-column: span 4;}
.row--large { grid-column: span 5;}
.row--xlarge { grid-column: span 8;}
.row--xxl { grid-column: span 12;}
.row--full { grid-column: span 16;}

/* --------- A G R E E I T E M S --------- */
.agree_item, .form-check {
display: flex;
align-items: flex-start;
gap: 0.5rem;
padding-bottom: 0.5rem;
}

.agree_item input[type="checkbox"], input[type="checkbox"] {
accent-color: black;
}

textarea {
width: 100%;
padding: 0.5rem;
}
/* --------- télécharger photos fichiers --------- */
/* .media-up {
display: flex;
flex-direction: row;
gap: 2rem;
border: none!important;
} */
/* RECETTE PHOTO - Garde texte natif, enlève bordure input */
/* .form-field photo-upload{
display: flex;
flex-direction: row;
}
.form-field input[type="file"] {
border: none !important;
background: transparent !important;
padding: 0;
height: auto;
} */

/* Texte "Aucun fichier choisi" */
/* .form-field input[type="file"]::-webkit-file-upload-button {
background: transparent;
border: none;
/* color: #666; */
/* padding: 0.5rem;
} */

/* Cache padding inutile */
/* input[type="file"]::-webkit-file-upload-button {
-webkit-appearance: none;
appearance: none;
}
/* Aligne label + input file sur une ligne */
.photo-upload {
display: flex;
align-self: end;
gap: 1rem;

}
.form-field.photo-upload label {
display: inline-block;
margin-bottom: 0; /* important pour ne pas créer de saut visuel */
white-space: nowrap; /* pour garder "Photo de la recette" sur une ligne */
}

/* Cache bordure et fond du champ file */
.file-inline {
border: none !important;
background: transparent !important;
padding: 0;
height: auto;
}

*{border: 1px dotted blue;}


/* ----------------- FORMULAIRE MODALE / login+reset ---------------- */
.modal-overlay { /* --- arriere champ modale --- */
position: fixed;
inset: 0;
background: rgba(255, 255, 255, 0.55);
display: flex;
align-items: center;
justify-content: center;
backdrop-filter: blur(2px);
z-index: 1000;
}
.modal_body{
width: 80%;
}
.modal-box {
max-width: 55rem;
background: linear-gradient( #00ff9d 5%, #FFFFFF 50%, #FFFFFF 100% );
padding: 3rem 4.5rem 3.9rem 4.5rem;
border-radius: 2.5rem;
border: 0.15rem solid black;
box-shadow: 0 0.8rem 0 black;
animation: modalFade 0.25s ease-out;
text-align: left;
font-size: 0.8rem;
position: relative; /* pour button close modale et overlay */
}

.close {
position: absolute;
top: -1.2rem;
right: -1rem;
background: transparent;
border: none;
font-size: 1.1rem;
font-weight:lighter;
cursor: pointer;
z-index: 10;
}
.close:hover {
font-weight: 900;
}

.modal-content {
margin-top: 6rem;
}

/* Animation d’apparition */
@keyframes modalFade {
from { opacity: 0; transform: translateY(-18px); }
to { opacity: 1; transform: translateY(0); }
}

.form-field {
margin-bottom: 0.5rem;
}

.form-field label {
display: block;
margin-bottom: 0.4rem;
border: none;
padding: 0;
}

.form-input, .form-field select {
width: 100%;
padding: 0.45em 1rem;
border-radius: 0.6rem;
border: 1px solid #000000;
background: #FFFFFF;
}

.form-input:focus, .form-field select:focus {
/* outline: 3px solid rgba(29, 255, 131, 0.5);
border-color: #00ff9d; */
outline: 3px solid black
}

/* --- mdp oublie --- */
.form-links {
margin-top: 0.8rem;
text-align: right;
}

.form-links a {
text-decoration: underline;
color: black;
}


/* --- MOT DE PASSE + BOUTON OEIL --- */
.password-wrapper {
position: relative;
}

.password-wrapper input {
width: 100%;
/* padding: 2.5rem; espace pour l’œil */
padding: 0.45em 1rem;
}

.password-toggle {
position: absolute;
top: 50%;
right: 0.8rem;
transform: translateY(-50%);
background: none;
border: none;
padding: 0;
cursor: pointer;
}

.password-toggle img.eye-icon {
width: 2.25rem;
height: auto;
}

/* --- BOUTON SUBMIT --- */
.form-submit { /* pour TOUT */
width: 60%;
max-width: 11rem;
padding: 0.45em 0.9rem;
border-radius: 0.6rem;
border: 1px solid #000000;
background: #FFFFFF;
text-align: end;
margin-top: 2.1rem;
margin-left: auto; /* pousse le bouton à droite */
display: block; /* pour que margin-left: auto fonctionne */
cursor: pointer;
transition: background 0.2s ease;
font-weight: 600;
}

.form-submit:hover {
background: #000000;
color: white;
}

.submit-full{
width: 100%;
}
/* --- MESSAGE D’ERREUR --- A SUPPRIMER */
/* .form-error {
background: #ffe5e5;
color: #a30000;
padding: 0.7rem;
border-radius: 6px;
margin-bottom: 1.2rem;
text-align: center;
font-weight: 600;
border: 1px solid #ffb3b3;
} */


{# <div class="form-field photo-upload">
            <label>Photo de la recette</label>
            
            <div class="file-input-wrapper">
                <input 
                    type="file" 
                    id="recette_photo"
                    name="{{ recetteForm.photo.vars.full_name }}"
                    accept="image/*"
                    class="file-hidden"
                >
            </div>  
                {{ form_errors(recetteForm.photo) }}
        </div>
        #}

{#            <div class="form-field photo-upload">
    {{ form_label(recetteForm.photo) }}

    {{ form_widget(recetteForm.photo, {
        attr: {
            class: 'file-inline',
            accept: 'image/*'
        }
    }) }}

    {{ form_errors(recetteForm.photo) }}
</div>
#}