<?php
if (empty($noIncMain)) {
    include_once 'includes/0inc.php';
    //$resultFetchUser=$user->fetch('', 'vmaury', '', 1, ($entitytotest > 0 ? $entitytotest : -1));
} else include_once 'includes/config.inc.php';
if (multiCompany) {
    include_once DOL_DOCUMENT_ROOT . '/custom/multicompany/class/actions_multicompany.class.php';
    $actionMC = new ActionsMulticompany($db);
}
dol_include_once('/devisclimatisation/lib/devisclimatisation.lib.php');
$title = 'Nouveau devis';
include 'header.php';
$form = new Form($db);
//print_r($_POST);
//print_r($_SESSION);
$entity = $_SESSION['dol_entity'];
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wizard Example</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/fontawesome/fontawesome.css">
    <!--Version CDN -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.6.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.6.0/mapbox-gl.css" rel="stylesheet"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!--    <script src='https://cdn.rawgit.com/naptha/tesseract.js/1.0.10/dist/tesseract.js'></script>-->
    <!--Version locale -->
    <!--    <script src="js/mapbox-glv2-6-0.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tesseract.js/1.0.10/tesseract.js" integrity="sha512-y88RwgIZiewj6+ZBX6LpFjph8lH3/6FOuuAdZ4/MxiSfr/ckhNMfue8pvQVqSTxf28QEPRU4PPbeH2u7Gk+xNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="assets/sweetalert.css">
	</body>
    <!--    <link href="css/mapbox-glv2-6-0.css" rel="stylesheet"/>-->
</head>
<body ng-app="dolimoni" ng-controller="DevisCesController">

<div class="progress-container">
	<div class="progress-bar">
		<div class="progress" id="progress"></div>
	</div>
	<div class="percentage" id="percentage">0%</div>
</div>
<div class="breadcrumb">
	<div class="breadcrumb-step active" id="breadcrumb-step1">Infos CLIENT</div>
	<div class="breadcrumb-step" id="breadcrumb-step2">Docs CLIENT</div>
	<div class="breadcrumb-step" id="breadcrumb-step3">Infos BÂTIMENT</div>
	<div class="breadcrumb-step" id="breadcrumb-step4">Infos ITE</div>
	<div class="breadcrumb-step" id="breadcrumb-step5">Infos FINANCEMENT</div>
	<div class="breadcrumb-step" id="breadcrumb-step6">Récapitulatif</div>
</div>
<div id="wizard">
	<?php

	require_once 'includes/steps/step1.php';
	require_once 'includes/steps/step2.php';
	require_once 'includes/steps/step3.php';
	require_once 'includes/steps/step4.php';
	require_once 'includes/steps/step5.php';
	require_once 'includes/steps/step6.php';

	?>

</div>



<script src="assets/sweetalert.min.js"></script>


<script src="assets/angular.min.js"></script>
<script src="assets/app/dolimoni.js"></script>
<script src="assets/app/controllers/devisCesController.js"></script>
<script src="assets/app/services/devisCesService.js"></script>
<script src="assets/app/directives/fileUploader.directive.js"></script>

<!-- ************ Scripts dynamiques de la page : ***********************************************-->
<!--  ne peuvent être placés dans un fichier js séparé pour disposer de php et de l'environnement Dolibarr -->
<script type="text/javascript" language="javascript">
	$(document).ready(function(){
        let nbClims = 1; //Stocke le nombre de clims pour ce devis
        //Chargement des valeurs pour les champs listes déroulantes des climatisations
        //VALEURS CLIMS typedinstallation
        valeurs_typedinstallation = new Object();
        <?php
        $valeurs = valeurs_selection_extrafield('typedinstallation', 'devisclimatisation_climtiers');
        foreach($valeurs as $key => $valeur) {
            ?>
            valeurs_typedinstallation["<?php print $key;?>"] = "<?php print $valeur;?>";
            <?php
        }
        ?>
        console.log(valeurs_typedinstallation);
        for (var cle in valeurs_typedinstallation) {
            $('#clim-install-type1').append('<option value="'+cle+'">'+valeurs_typedinstallation[cle]+'</option>');
        }
        //VALEURS CLIMS marque
        valeurs_marque = new Object();
        <?php
        $valeurs = valeurs_selection_extrafield('marque', 'devisclimatisation_climtiers');
        foreach($valeurs as $key => $valeur) {
            ?>
            valeurs_marque["<?php print $key;?>"] = "<?php print $valeur;?>";
            <?php
        }
        ?>
        console.log(valeurs_marque);
        for (var cle in valeurs_marque) {
            $('#clim-brand1').append('<option value="'+cle+'">'+valeurs_marque[cle]+'</option>');
            $('#old-clim-brand1').append('<option value="'+cle+'">'+valeurs_marque[cle]+'</option>');
        }
        //VALEURS CLIMS Puissance BTU
        valeurs_puissancebtu = new Object();
        <?php
        $valeurs = valeurs_selection_extrafield('puissancebtu', 'devisclimatisation_climtiers');
        foreach($valeurs as $key => $valeur) {
            ?>
            valeurs_puissancebtu["<?php print $key;?>"] = "<?php print $valeur;?>";
            <?php
        }
        ?>
        console.log(valeurs_puissancebtu);
        for (var cle in valeurs_puissancebtu) {
            $('#clim-btu1').append('<option value="'+cle+'">'+valeurs_puissancebtu[cle]+'</option>');
            $('#old-clim-btu1').append('<option value="'+cle+'">'+valeurs_puissancebtu[cle]+'</option>');
        }
        //VALEURS CLIMS Classification énergétique
        valeurs_classificationenergetique = new Object();
        <?php
        $valeurs = valeurs_selection_extrafield('classificationenergetique', 'devisclimatisation_climtiers');
        foreach($valeurs as $key => $valeur) {
            ?>
            valeurs_classificationenergetique["<?php print $key;?>"] = "<?php print $valeur;?>";
            <?php
        }
        ?>
        console.log(valeurs_classificationenergetique);
        for (var cle in valeurs_classificationenergetique) {
            $('#clim-energy-class1').append('<option value="'+cle+'">'+valeurs_classificationenergetique[cle]+'</option>');
            $('#old-clim-energy-class1').append('<option value="'+cle+'">'+valeurs_classificationenergetique[cle]+'</option>');
        }
        //VALEURS CLIMS Pièce concernée
        valeurs_piececoncernee = new Object();
        <?php
        $valeurs = valeurs_selection_extrafield('piececoncernee', 'devisclimatisation_climtiers');
        foreach($valeurs as $key => $valeur) {
            ?>
            valeurs_piececoncernee["<?php print $key;?>"] = "<?php print $valeur;?>";
            <?php
        }
        ?>
        console.log(valeurs_piececoncernee);
        for (var cle in valeurs_piececoncernee) {
            $('#clim-room1').append('<option value="'+cle+'">'+valeurs_piececoncernee[cle]+'</option>');
        }
        //VALEURS CLIMS Type de Pose
        valeurs_typedepose = new Object();
        <?php
        $valeurs = valeurs_selection_extrafield('typedepose', 'devisclimatisation_climtiers');
        foreach($valeurs as $key => $valeur) {
            ?>
            valeurs_typedepose["<?php print $key;?>"] = "<?php print $valeur;?>";
            <?php
        }
        ?>
        console.log(valeurs_typedepose);
        for (var cle in valeurs_typedepose) {
            $('#clim-pose1').append('<option value="'+cle+'">'+valeurs_typedepose[cle]+'</option>');
        }
        //VALEURS CLIMS Longueur de liaison estimée
        valeurs_longueurdelaisonestimee = new Object();
        <?php
        $valeurs = valeurs_selection_extrafield('longueurdelaisonestimee', 'devisclimatisation_climtiers');
        foreach($valeurs as $key => $valeur) {
            ?>
            valeurs_longueurdelaisonestimee["<?php print $key;?>"] = "<?php print $valeur;?>";
            <?php
        }
        ?>
        console.log(valeurs_longueurdelaisonestimee);
        for (var cle in valeurs_longueurdelaisonestimee) {
            $('#clim-liaison1').append('<option value="'+cle+'">'+valeurs_longueurdelaisonestimee[cle]+'</option>');
        }


        //******  gestion des ajouts/suppr de clims avec renumérotation dynamique des id et name des champs *****
        //** La renumérotation dynamique des id et name des champs permet d'avoir des valeurs uniques, et ensuite
        //** de récupérer leurs valeurs à soumission du formulaire

        //Ajoute une clim à la liste
        $(".add-clim-button").click(function(){
            //Si click sur le bouton "Ajouter une autre clim"
            nbClims++;
            console.log("Nombre de clims : "+nbClims);
            const climContainer = document.createElement('div'); //Créer une nouvelle DIV pour le container de la nouvelle clim
            climContainer.className = "clim-container";
            climContainer.setAttribute("id", "clim-container"+nbClims);
                chaineClim = `
                <br>
                <fieldset>
                    <legend>Infos Clim ${nbClims}</legend>
                    <div class="field">
                            <label for="clim-install-type">Type d'installation :</label>
                            <select id="clim-install-type${nbClims}" name="clim-install-type${nbClims}" class="clim-install-type" data-numeroclim="${nbClims}">`;
                            for (var cle in valeurs_typedinstallation) {
                                chaineClim += '<option value="'+cle+'">'+valeurs_typedinstallation[cle]+'</option>';
                            }
                chaineClim += `
                            </select>
                        </div>
                        <div id="replacement-details${nbClims}" class="replacement-details" style="display:none;">
                            <div class="field">
                                <label for="old-clim-brand">Ancienne Marque :</label>
                                <select id="old-clim-brand${nbClims}" name="old-clim-brand${nbClims}" class="old-clim-brand">`
                                for (var cle in valeurs_marque) {
                                    chaineClim += '<option value="'+cle+'">'+valeurs_marque[cle]+'</option>';
                                }
                chaineClim += `
                                </select>
                            </div>
                            <div class="field">
                                <label for="old-clim-btu">Ancienne Puissance BTU :</label>
                                <select id="old-clim-btu${nbClims}" name="old-clim-btu${nbClims}" class="old-clim-btu">`
                                for (var cle in valeurs_puissancebtu) {
                                    chaineClim += '<option value="'+cle+'">'+valeurs_puissancebtu[cle]+'</option>';
                                }
                chaineClim += `
                                </select>
                            </div>
                            <div class="field">
                                <label for="old-clim-energy-class">Ancienne Classification énergétique :</label>
                                <select id="old-clim-energy-class${nbClims}" name="old-clim-energy-class${nbClims}" class="old-clim-energy-class">`
                                for (var cle in valeurs_classificationenergetique) {
                                    chaineClim += '<option value="'+cle+'">'+valeurs_classificationenergetique[cle]+'</option>';
                                }
                chaineClim += `
                                </select>
                            </div>
                            <hr>
                        </div>
                    <div class="field">
                        <label for="clim-brand">Marque :</label>
                        <select id="clim-brand${nbClims}" name="clim-brand${nbClims}" class="clim-brand">`
                        for (var cle in valeurs_marque) {
                            chaineClim += '<option value="'+cle+'">'+valeurs_marque[cle]+'</option>';
                        }
                chaineClim += `
                        </select>
                    </div>
                    <div class="field">
                        <label for="clim-btu">Puissance BTU :</label>
                        <select id="clim-btu${nbClims}" name="clim-btu${nbClims}" class="clim-btu">`
                        for (var cle in valeurs_puissancebtu) {
                            chaineClim += '<option value="'+cle+'">'+valeurs_puissancebtu[cle]+'</option>';
                        }
                chaineClim += `
                        </select>
                    </div>
                    <div class="field">
                        <label for="clim-energy-class">Classification énergétique :</label>
                        <select id="clim-energy-class${nbClims}" name="clim-energy-class${nbClims}" class="clim-energy-class">`
                        for (var cle in valeurs_classificationenergetique) {
                            chaineClim += '<option value="'+cle+'">'+valeurs_classificationenergetique[cle]+'</option>';
                        }
                chaineClim += `
                        </select>
                    </div>
                    <div class="field">
                        <label for="clim-room">Pièce concernée :</label>
                        <select id="clim-room${nbClims}" name="clim-room${nbClims}" class="clim-room">`
                        for (var cle in valeurs_piececoncernee) {
                            chaineClim += '<option value="'+cle+'">'+valeurs_piececoncernee[cle]+'</option>';
                        }
                chaineClim += `
                        </select>
                    </div>
                    <div class="field">
                        <label for="clim-emplacement">Emplacement précis de la ou des pièces :</label>
                        <input type="text" id="clim-emplacement1" name="clim-emplacement1" class="clim-emplacement">
                    </div>
                    <div class="field">
                        <label for="clim-pose">Type de Pose :</label>
                        <select id="clim-pose${nbClims}" name="clim-pose${nbClims}" class="clim-pose">`
                        for (var cle in valeurs_typedepose) {
                            chaineClim += '<option value="'+cle+'">'+valeurs_typedepose[cle]+'</option>';
                        }
                chaineClim += `
                        </select>
                    </div>
                    <div class="field">
                            <label for="clim-liaison">Longueur de liaison estimée :</label>
                            <select id="clim-liaison${nbClims}" name="clim-liaison${nbClims}" class="clim-liaison">`
                            for (var cle in valeurs_longueurdelaisonestimee) {
                                chaineClim += '<option value="'+cle+'">'+valeurs_longueurdelaisonestimee[cle]+'</option>';
                            }
                chaineClim += `
                        </select>
                    </div>
                    <div class="field">
                        <label for="clim-alim-presente">Alimentation présente :</label>
                        <select id="clim-alim-presente${nbClims}" name="clim-alim-presente${nbClims}" class="clim-alim-presente">
                            <option value="1" selected>OUI</option>
                            <option value="2">NON</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="clim-alim-conforme">Alimentation conforme :</label>
                        <select id="clim-alim-conforme${nbClims}" name="clim-alim-conforme${nbClims}" class="clim-alim-conforme">
                            <option value="1" selected>OUI</option>
                            <option value="2">NON</option>
                        </select>
                    </div>
                </fieldset>
                <button type="button" class="remove-clim-button" data-numeroclim="${nbClims}" class="remove-clim-button">Retirer</button>
            `;
            climContainer.innerHTML = chaineClim;
            const step4 = document.getElementById('step4'); //On va insérer cette chaineClim juste avant le bouton "Ajouter une autre clim", qui est un élément de la div "step4"
            const addClimContainer = document.getElementById('add-clim-container'); // On obtient la référence à l'élément contenant le bouton
            step4.insertBefore(climContainer, addClimContainer); // Insère le nouveau conteneur avant le conteneur du bouton
            renumerotation_champs_clims(); //Renumérote séquentiellement les id et name des champs
            updateClimLegends(); //Renumérote séquentiellement les légendes "Infos Clim xx"
        }); //FIN $(".add-clim-button").click

        //Masque ou affiche les détails de l'ancienne clim suivant le type d'installation : Nouvelle Installation ou Remplacement
        $("#step4").on("change", ".clim-install-type",function(event) {
            //Accès aux champs "Type d'installation" des clims en tant que sous éléments de l'élément d'id "step4"
            //On ne peut accéder directement à ces champs, qui ont été générés dynamiquement, et ne font donc pas partie
            //du DOM initial à l'écoute d'évènements
            if ($( this ).val() === 'Remplacement') {
                $("#replacement-details"+$( this ).data('numeroclim')).show();//data('numeroclim') retourne la valeur de l'attribut data-numeroclim
            } else {
                $("#replacement-details"+$( this ).data('numeroclim')).hide();//data('numeroclim') retourne la valeur de l'attribut data-numeroclim
            }
        }); //FIN $(".remove-clim-button").click

        //Retire une clim et renumérote séquentiellement les clims restantes
        $("#step4").on("click", ".remove-clim-button",function(event) {
            //Accès aux boutons "Retirer" des clims en tant que sous éléments de l'élément d'id "step4"
            //On ne peut accéder directement à ces boutons, qui ont été générés dynamiquement, et ne font donc pas partie
            //du DOM initial à l'écoute d'évènements
            nbClims--;
            console.log("Nombre de clims : "+nbClims);
            //Suppression du container de l'ensemble des champs de la clim retirée
            $("#clim-container"+$( this ).data('numeroclim')).remove(); //data('numeroclim') retourne la valeur de l'attribut data-numeroclim
            renumerotation_champs_clims(); //Renumérote séquentiellement les id et name des champs
            updateClimLegends(); //Renumérote séquentiellement les légendes "Infos Clim xx"
        }); //FIN $(".remove-clim-button").click


        function renumerotation_champs_clims() {
            //Parcourir tous les champs de la liste des clims sur la base de leur classe,
            //et renuméroter séquentiellement leurs id et name
            //Cela inclus les containers de clims, les div replacement-details et les boutons Retirer
            compteurClims = 1;
            $('.clim-container').each(function () { //Renuméroter les id des containers
                $( this ).prop('id', 'clim-container'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.replacement-details').each(function () { //Renuméroter les id des containers
                $( this ).prop('id', 'replacement-details'+compteurClims);
                compteurClims++;
            });
            compteurClims = 2; //Attention : les id des boutons "Retirer" commencent à 2 (pas de bouton "Retirer" sur la 1ere clim !!! )
            $('.remove-clim-button').each(function () { //Renuméroter les attributs data-numeroclim
                $( this ).data('numeroclim', compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.clim-install-type').each(function () {
                $( this ).prop('id', 'clim-install-type'+compteurClims);
                $( this ).prop('name', 'clim-install-type'+compteurClims);
                //console.log( "avant="+$( this ).data('numeroclim'));
                $( this ).data('numeroclim', compteurClims);
                //console.log( "après="+$( this ).data('numeroclim'));
                compteurClims++;
            });
            compteurClims = 1;
            $('.old-clim-brand').each(function () {
                $( this ).prop('id', 'old-clim-brand'+compteurClims);
                $( this ).prop('name', 'old-clim-brand'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.old-clim-btu').each(function () {
                $( this ).prop('id', 'old-clim-btu'+compteurClims);
                $( this ).prop('name', 'old-clim-btu'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.old-clim-energy-class').each(function () {
                $( this ).prop('id', 'old-clim-energy-class'+compteurClims);
                $( this ).prop('name', 'old-clim-energy-class'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.clim-brand').each(function () {
                $( this ).prop('id', 'clim-brand'+compteurClims);
                $( this ).prop('name', 'clim-brand'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.clim-btu').each(function () {
                $( this ).prop('id', 'clim-btu'+compteurClims);
                $( this ).prop('name', 'clim-btu'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.clim-energy-class').each(function () {
                $( this ).prop('id', 'clim-energy-class'+compteurClims);
                $( this ).prop('name', 'clim-energy-class'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.clim-room').each(function () {
                $( this ).prop('id', 'clim-room'+compteurClims);
                $( this ).prop('name', 'clim-room'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.clim-emplacement').each(function () {
                $( this ).prop('id', 'clim-emplacement'+compteurClims);
                $( this ).prop('name', 'clim-emplacement'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.clim-pose').each(function () {
                $( this ).prop('id', 'clim-pose'+compteurClims);
                $( this ).prop('name', 'clim-pose'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.clim-liaison').each(function () {
                $( this ).prop('id', 'clim-liaison'+compteurClims);
                $( this ).prop('name', 'clim-liaison'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.clim-alim-presente').each(function () {
                $( this ).prop('id', 'clim-alim-presente'+compteurClims);
                $( this ).prop('name', 'clim-alim-presente'+compteurClims);
                compteurClims++;
            });
            compteurClims = 1;
            $('.clim-alim-conforme').each(function () {
                $( this ).prop('id', 'clim-alim-conforme'+compteurClims);
                $( this ).prop('name', 'clim-alim-conforme'+compteurClims);
                compteurClims++;
            });
        } //FIN function renumerotation_champs_clims


        //***** FONCTION nextStep : passe d'une étape à l'autre dans l'affichage du formulaire ***
        //                          Lors du passage à l'étape 5, génère dynamiquement le contenu récapitulatif


        //***** Génération du contenu récapitulatif en dernière étape ***
        //      appelé sur click bouton "Suivant" pour affichage dernière étape
		$("#btnEtape5").click(function(event) {
			//$( this ).data('contrat') retourne la valeur de l'attribut data-contrat (id du contrat) de l'élément clické/sélectionné
			//$( this ).data('option') retourne la valeur de l'attribut data-option (id du produit) de l'élément clické/sélectionné
			//Appel Ajax. On envoie en paramètre l'id de l'équipement et le code du suivi à sauvegarder
            console.log(valeurs_formulaire_clims());

            $.post(	'ajax_calcul_recap_devis.php', //Ajax appelé
				{ 	'listeClims': JSON.stringify(valeurs_formulaire_clims())
						}, //Paramètres envoyés à l'ajax
				function(data) {
					console.log("Retour de l'ajax "+data);
					$("#recapdevis").html(data);
				} //FIN function(data) {
			);	//FIN $.post

		}); //FIN $("#btnEtape5").click(function(event) {

        //** Retourne une structure json contenant les valeurs des formulaires de clims
        function valeurs_formulaire_clims() {
            listeClims = new Array();
            for (var i=1; i <= nbClims; i++) {
                uneClim = new Object();
                uneClim["typedinstallation"] = $("#clim-install-type"+i).val();
                uneClim["anciennemarque"] = $("#old-clim-brand"+i).val();
                uneClim["anciennepuissancebtu"] = $("#old-clim-btu"+i).val();
                uneClim["ancienneclassificationenergetique"] = $("#old-clim-energy-class"+i).val();
                uneClim["marque"] = $("#clim-brand"+i).val();
                uneClim["puissancebtu"] = $("#clim-btu"+i).val();
                uneClim["classificationenergetique"] = $("#clim-energy-class"+i).val();
                uneClim["typedepose"] = $("#clim-pose"+i).val();
                uneClim["longueurdelaisonestimee"] = $("#clim-liaison"+i).val();
                uneClim["piececoncernee"] = $("#clim-room"+i).val();
                uneClim["emplacement_des_pieces"] = $("#clim-emplacement"+i).val();
                uneClim["alim_presente"] = $("#clim-alim-presente"+i).val();
                uneClim["alim_conforme"] = $("#clim-alim-conforme"+i).val();
                listeClims.push(uneClim);
            }
            return listeClims;
        } //FIN function valeurs_formulaire_clims() {


        // Gestion de la soumission du formulaire, y compris upload des pièces jointes ***
        // NOTA : bien noter que sur on click, on appelle une async function, de façon à pouvoir utiliser un await sur le fetch
        //        afin d'attendre que l'upload soit terminé avant de continuer le traitement.
        $('#btnCreerDevis').on('click', async function (event) {
            $(".loader").fadeIn("50"); //Faire apparaitre le loader
            const formulaireUpload = document.getElementById("formcreerdevis"); //Pointe vers le form, qui contient tous les champs sauf ceux des clims
            formData = new FormData(formulaireUpload); //Création d'un formulaire virtuel à partir du formulaire qui contient le champ d'upload
            formData.append("listeClims", JSON.stringify(valeurs_formulaire_clims())); //On ajoute un champ fictif au formData avant de le soumettre au traitement php
            try {
                /* fetch() prend en 1er paramètre l'url et en second paramètre
                les options. Ici, nous indiquons que notre requête sera en POST
                et que le corps de la requête sera constitué de nos formData.
                Encapsulé dans le formData, on trouvera entre autre le fichier uploadé, qui
                pourra être récupéré par le traitement ajax en PHP via $_FILES */
                let reponse = await fetch('ajax_creer_devis.php',
                {
                    method: "POST",
                    body: formData,
                });
                // On affiche un message suivant le résultat de la requête
                if (reponse.ok) { // if HTTP-status is 200-299
                    //reponse est un objet de type Promise => c'est un objet complexe disposant de pas mal de
                    //méthodes pour exploiter notamment les données retournées par l'ajax. Voir doc sur cette classe Promise
                    // obtenir le corps de réponse, suivant le format retourné par l'ajax php
                    //let json = await reponse.json();
                    let reponsetexte = await reponse.text(); //Pour récupérer une réponse simple en format text, ou bien await reponse.json() pour récupérer une réponse en json, à parser ensuite
                    //maj_lignes_suivis_realises();
                    $(".loader").fadeOut("200"); //Faire disparaire le loaded
                    console.log(reponsetexte);
                    valeurs = JSON.parse(reponsetexte);
                    $("#messagecreationdevis").html(valeurs.message); //Afficher le message en retour en bas du step5
                    window.open('<?php print DOL_MAIN_URL_ROOT;?>/comm/propal/card.php?id='+valeurs.idpropal1, '_blank');
                    if (valeurs.idpropal2 > 0) {
                        window.open('<?php print DOL_MAIN_URL_ROOT;?>/comm/propal/card.php?id='+valeurs.idpropal2, 'devisoptions');
                    }
                } else {
                    $(".loader").fadeOut("200"); //Faire disparaire le loaded
                    alert("HTTP-Error: " + reponse.status);
                }
            } catch (error) {
                $(".loader").fadeOut("200"); //Faire disparaire le loaded
                alert("Erreur upload");
            }
            //$(".loader").fadeOut("200");
        }); //FIN $('#btnCreerDevis').on('click', async function (event) {


    }); //FIN $(document).ready(function(){
</script>

</body>


<?php
include 'footer.php';
