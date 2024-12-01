<?php
/* Copyright (C) 2023		David Roubaud		<contact@studioroubaud.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file htdocs/custom/devisclimatisation/www/ajax_creer_devis_ite.php
 * \brief à partir d'une soumission asynchrone de formulaire, crée une fiche Tiers, lui ajoute des documents lié ainsi que des clims liées, et enfin crée un devis
 */

 /*
if (empty($noIncMain)) {
    include_once 'includes/0inc.php';
    //$resultFetchUser=$user->fetch('', 'vmaury', '', 1, ($entitytotest > 0 ? $entitytotest : -1));
} else include_once 'includes/config.inc.php';
if (multiCompany) {
    include_once DOL_DOCUMENT_ROOT . '/custom/multicompany/class/actions_multicompany.class.php';
    $actionMC = new ActionsMulticompany($db);
}
*/

if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL', 1); // Disables token renewal
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU', '1');
if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML', '1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX', '1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC', '1');
if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK', '1');
if (empty($_GET['keysearch']) && ! defined('NOREQUIREHTML')) define('NOREQUIREHTML', '1');

// Load Dolibarr environment
$res=0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (! $res && ! empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res=@include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp=empty($_SERVER['SCRIPT_FILENAME'])?'':$_SERVER['SCRIPT_FILENAME'];$tmp2=realpath(__FILE__); $i=strlen($tmp)-1; $j=strlen($tmp2)-1;
while($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i]==$tmp2[$j]) { $i--; $j--; }
if (! $res && $i > 0 && file_exists(substr($tmp, 0, ($i+1))."/main.inc.php")) $res=@include substr($tmp, 0, ($i+1))."/main.inc.php";
if (! $res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i+1)))."/main.inc.php")) $res=@include dirname(substr($tmp, 0, ($i+1)))."/main.inc.php";
// Try main.inc.php using relative path
if (! $res && file_exists("../main.inc.php")) $res=@include "../main.inc.php";
if (! $res && file_exists("../../main.inc.php")) $res=@include "../../main.inc.php";
if (! $res && file_exists("../../../main.inc.php")) $res=@include "../../../main.inc.php";
if (! $res) die("Include of main fails");
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT.'/comm/propal/class/propal.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/propal.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
dol_include_once('/devisclimatisation/lib/devisclimatisation.lib.php');
dol_include_once('/devisclimatisation/class/climtiers.class.php');
$entity = $_SESSION['dol_entity'];

//Récupérer les paramètres de l'appel Ajax

$devis = GETPOST('devis','none');

dol_syslog(join(',', $_GET));

function createTiers($db,$conf,$devis)
{
	//***** Etape 1 : créer la fiche Tiers *******/
	$prospect = new Societe($db);



	if(isset($devis['customer']['id'])){
		$prospect->fetch($devis['customer']['id']);
	}

	$prospect->entity = $conf->entity;



	$prospect->name = trim($devis['customer']['name']);
	$prospect->phone = trim($devis['customer']['phone']);
	$prospect->email = trim($devis['customer']['email']);
	$prospect->address =trim($devis['customer']['address']);
	$prospect->zip = trim($devis['customer']['zip']);
	$prospect->town = trim($devis['customer']['town']);
	$prospect->cond_reglement_id = 6;
	$prospect->mode_reglement_id = 3;
	$prospect->fk_departement = 296;
	$prospect->fk_pays = 1;
	$prospect->client = 2; //0=rien , 1=client, 2=prospect, 3=client et prospect
	$prospect->code_client = -1; //Signifie que le code client sera généré automatiquement
	$prospect->fournisseur = 0;

	if ( $devis['customer']['clientType'] == 'individual') {
		//Si prospect type Particulier
		$prospect->typent_id = 8; //Type de Tiers = Particulier
		$prospect->fax = trim($devis['customer']['fax']);
	} else {
		//Si prospect type Société
		$prospect->typent_id = 3; //Type de Tiers = PME/PMI
		$prospect->idprof2 = trim($devis['customer']['idprof2']);
	}

	return $prospect;
}

function populateTiersExtraFields($devis,$prospect)
{

	$extraFieldsCustomer = array_filter($devis['customer'], function($field) {
		return isset($field['type']) && $field['type'] === 'extra';
	});

	$extraFieldsCustomerDocs = array_filter($devis['customer']['docs'], function($field) {
		return isset($field['type']) && $field['type'] === 'extra';
	});

	$extraFieldsBuilding = array_filter($devis['building'], function($field) {
		return isset($field['type']) && $field['type'] === 'extra';
	});



	$extraFields = array_merge($extraFieldsCustomer,$extraFieldsCustomerDocs, $extraFieldsBuilding);

	foreach ($extraFields as $key=> $extraField){
		$prospect->array_options['options_'.$key] =  trim($extraField['value']);
	}

	$prospect->array_options['options_etatop'] = 10;
	$prospect->array_options['options_previsite'] = 1;

	return $prospect;
}
function populatePropalExtraFields($devisForm,$propal)
{

	$extraFields = array_filter($devisForm['finance'], function($fild) {
		return isset($fild['type'],$field['value']) && $fild['type'] === 'extra';
	});

	foreach ($extraFields as $key=> $extraField){
		$propal->array_options['options_'.$key] =  trim($extraField['value']);
	}

	return $propal;
}


/**
 * @todo Mettre les bons attributs de contact
 * @param $db
 * @param $clientId
 * @param $devis
 * @param $prospect
 * @return mixed
 */
function addContacts($db,$clientId,$devis,$prospect)
{


	if(isset($devis['customer']['showContact']) && $devis['customer']['showContact']==='true'){
		$contact =new Contact($db);
		$contact->socid = $clientId;
		$contact->lastname = isset($devis['customer']['contactFullname']) ? $devis['customer']['contactFullname']:'';
		$contact->title = isset($devis['customer']['contactRelation']) ? $devis['customer']['contactRelation']:'';
		$contact->phone_pro = isset($devis['customer']['contactMobile']) ? $devis['customer']['contactMobile']:'';
		$contact->fk_departement = 296;
		$contact->fk_pays = 1;
		$contact->create($user);
	}

	if(isset($devis['customer']['differentAddress']) && $devis['customer']['differentAddress']==='true'){
		$contact =new Contact($db);
		$contact->socid = $clientId;
		$contact->address = isset($devis['customer']['chantierAddress']) ? $devis['customer']['chantierAddress']:'';
		$contact->zip = isset($devis['customer']['chantierPostcode']) ? $devis['customer']['chantierPostcode']:'';
		$contact->town = isset($devis['customer']['chantierCity']) ? $devis['customer']['chantierCity']:'';
		$contact->fk_departement = 296;
		$contact->fk_pays = 1;
		$contact->create($user);
	}

	if(isset($devis['finance']['showFinanceContact']) && $devis['finance']['showFinanceContact']==='true'){
		$contact =new Contact($db);
		$contact->socid = $clientId;
		$contact->lastname = isset($devis['finance']['financeContactFullname']) ? $devis['finance']['financeContactFullname']:'';
		$contact->phone_pro = isset($devis['finance']['financeContactRelation']) ? $devis['finance']['financeContactRelation']:'';
		$contact->financeContactMobile = isset($devis['finance']['financeContactMobile']) ? $devis['finance']['financeContactMobile']:'';
		$contact->fk_departement = 296;
		$contact->fk_pays = 1;
		$contact->create($user);
	}

	return $prospect;
}

function createDevis($db,$devisForm,$prospect,$clientId,$user,$langs){
	$propal = new Propal($db);
	$propal->date = dol_now();
	$propal->socid = $clientId;
	$propal->ref_client = '';
	$propal->cond_reglement_id = 6;
	$propal->mode_reglement_id = 3;
	$idDevis1 = $propal->create($user);
	$propal->fetch($idDevis1); // rechargement nécessaire pour générer le PDF
	foreach($devisForm['products'] as $product) {

		if($product['quantity'] <= 0){
			continue;
		}

		$service = new Product($db);
		$service->fetch('',$product['name']); //A tester




		//Ajouter les lignes de devis des services abonnements clims
		$idLigne = $propal->addline(
			$service->label.'<br>'.$service->description, //Description
			$service->price, //Prix unitaire HT
			$product["quantity"], //Qty
			$service->tva_tx, //Taux TVA du service, qui n'est pas par défaut get_default_tva($clientId, $clientId)
			0, //Deprecated
			0, //Deprecated
			$service->id, //fk_product
			0,
			'HT',
			0,
			0,
			1 //On force type = service, contrairement à la doc de addline qui indique : Type of line (0=product, 1=service). Not used if fk_product is defined, the type of product is used.

		//Les autres caractéristiques de la ligne de devis prennent les valeurs par défaut
		);
	} //FIN foreach($tab_clims as $clim) {

	$privateNote = getPrivateNote($devisForm['ite']['facades']);
	$propal->note_private = $privateNote;

	$propal = populatePropalExtraFields($devisForm,$propal);


	$propal->update($user);
	$propal->fetch($idDevis1); //Recharger le devis pour que les détails du PDF soient complets
	$propal->valid($user); //Valider le devis, pour lui affecter un numéro de référence
	$outputlangs = $langs;


	//Todo A corriger
	$OKpdf = $propal->generateDocument('', $outputlangs, 0, 0, 0); //Générer PDF avec modèle par défaut
	$ref1 = $propal->ref;
	return $ref1;
}

function getPrivateNote($facades){
	$privateNote = '';
	$countFacades = 0;
	$windowCount = 0;
	$doorCount = 0;
	$faucetCount = 0;
	$gutterCount = 0;
	$climCount = 0;
	$hublotCount = 0;
	foreach ($facades as $facade) {
		$countFacades++;
		if($facade['windowCount'] > 0){
			$windowCount+=$facade['windowCount'];
		}
		if($facade['doorCount'] > 0){
			$doorCount+=$facade['doorCount'];
		}
		if($facade['faucetCount'] > 0){
			$faucetCount+=$facade['faucetCount'];
		}
		if($facade['gutterCount'] > 0){
			$gutterCount+=1;
		}
		if($facade['climCount'] > 0){
			$climCount+=$facade['climCount'];
		}
		if($facade['hublotCount'] > 0){
			$hublotCount+=$facade['hublotCount'];
		}
	}

	$privateNote = "Il y a $countFacades facades, $windowCount fenêtre, $doorCount portes, $faucetCount robinet, $gutterCount gouttière,
	$climCount climatisation et $hublotCount hublot";

	return $privateNote;
}


function uploadTierFiles($conf,$devis,$id)
{

	$chemin = $conf->societe->dir_output.'/'.$id;

	copyFiles('uploads/'.$devis['tmp'].'/customer/', $chemin);
}

function uploadDevisFiles($conf,$devis,$devisRef)
{

	$chemin = $conf->propal->dir_output.'/'.$devisRef;

	copyFiles('uploads/'.$devis['tmp'].'/devis/', $chemin);

}

function copyFiles($sourceDir, $destinationDir) {
	// Ensure the source directory exists
	if (!is_dir($sourceDir)) {
		return ;
	}

	// Create the destination directory if it doesn't exist
	if (!is_dir($destinationDir)) {
		mkdir($destinationDir, 0777, true);
	}

	// Open the source directory
	$files = scandir($sourceDir);

	foreach ($files as $file) {
		if ($file === '.' || $file === '..') {
			continue; // Skip the current and parent directory entries
		}

		$sourcePath = $sourceDir . DIRECTORY_SEPARATOR . $file;
		$destPath = $destinationDir . DIRECTORY_SEPARATOR . $file;

		// Copy file or recursively handle directories
		if (is_file($sourcePath)) {
			copy($sourcePath, $destPath);
		} elseif (is_dir($sourcePath)) {
			// Recursively copy directories
			copyFiles($sourcePath, $destPath);
		}
	}
}



function removeTmpDir($dir){

	if (!is_dir($dir)) {
		return ;
	}
	$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($it,
		RecursiveIteratorIterator::CHILD_FIRST);
	foreach($files as $file) {
		if ($file->isDir()){
			rmdir($file->getPathname());
		} else {
			unlink($file->getPathname());
		}
	}
	rmdir($dir);
}


$response = array(
	'status'=>'success'
);


try {


	$prospect = createTiers($db,$conf,$devis);

	if(isset($devis['customer']['id'])){
		$prospect->update($devis['customer']['id'],$user);
		$clientId = $devis['customer']['id'];
	}else{
		$clientId = $prospect->create($user);
	}

	if ($clientId < 0) {
		throw new Exception("Erreur en création du prospect");
	}

	//Affecter l'utilisateur comme commercial du client
	$prospect->add_commercial($user, $user->id);

	$prospect->fetch($clientId);

	$prospect = populateTiersExtraFields($devis,$prospect);
	$prospect = addContacts($db,$clientId,$devis,$prospect);

	$prospect->update($clientId,$user);  //MAJ du prospect avec les extrafields remplis

    uploadTierFiles($conf,$devis,$clientId);

//	unlink('uploads/'.$devis['tmp']);

	$devisRef = createDevis($db,$devis,$prospect,$clientId,$user,$langs);
	uploadDevisFiles($conf,$devis,$devisRef);

	$devisTmpDir = $devis['tmp'];

	removeTmpDir(__DIR__."/uploads/$devisTmpDir");


	$response['msg'] = "Le devis a été bien créé";
	echo json_encode($response);


}catch (Exception $e){
	$response['status'] = "error";
	$response['msg'] = $e->getMessage();
	echo json_encode($response);
}

return;


//***** Etape 2 : créer les devis *******/
//On constitue des tableaux de détails récap des clims et options à faire figurer aux devis,
//et on en profite pour créer les clims à rattacher au prospect
$tab_clims = array(); //Tableau des clims avec qté par puissance
$tab_options = array(); //Tableau des options avec qté par puissance
//Remplissage du tableau récap des clims
foreach($listeClims as $key => $clim) {
    //Chercher le service d'abonnement sur la base de la puissance BTU
    $ref_service_abonnement = "ABO-".$clim["puissancebtu"]."-G5A";
    $service = new Product($db);
    $service->fetch('',$ref_service_abonnement); //Charger le service sur la base de sa ref
    if (!empty($tab_clims[$clim["puissancebtu"]])) {
        //Si on a déjà une ligne pour cette puissance BTU dans $tab_clims, incrémenter la qté
        $tab_clims[$clim["puissancebtu"]]["qte"]++;
    } else {
        //Sinon, créer une ligne pour cette puissance BTU dans $tab_clims
        $tab_clims[$clim["puissancebtu"]] = array("idservice" => $service->id,
                                                    "tauxtva" => $service->tva_tx,
                                                    "puht" => $service->price,
                                                    "qte" => 1,
                                                    "description" => $service->label.'<br>'.$service->description
                                                );
    }
    //Créer la clim à rattacher au prospect :
    $climTiers = new ClimTiers($db);
    $climTiers->ref = "Clim".$key." : ".$clim["puissancebtu"].' BTU';
    $climTiers->fk_soc = $clientId;
    $idClimTiers = $climTiers->create($user);
    $climTiers->fetch($idClimTiers);
    $climTiers->array_options['options_typedinstallation'] = $clim['typedinstallation'];
    $climTiers->array_options['options_anciennemarque'] = $clim['anciennemarque'];
    $climTiers->array_options['options_anciennepuissancebtu'] = $clim['anciennepuissancebtu'];
    $climTiers->array_options['options_ancienneclassificationenergetique'] = $clim['ancienneclassificationenergetique'];
    $climTiers->array_options['options_marque'] = $clim['marque'];
    $climTiers->array_options['options_puissancebtu'] = $clim['puissancebtu'];
    $climTiers->array_options['options_classificationenergetique'] = $clim['classificationenergetique'];
    $climTiers->array_options['options_typedepose'] = $clim['typedepose'];
    $climTiers->array_options['options_longueurdelaisonestimee'] = $clim['longueurdelaisonestimee'];
    $climTiers->array_options['options_piececoncernee'] = $clim['piececoncernee'];
    $climTiers->array_options['options_emplacement_des_pieces'] = $clim['emplacement_des_pieces'];
    $climTiers->array_options['options_alim_presente'] = $clim['alim_presente'];
    $climTiers->array_options['options_alim_conforme'] = $clim['alim_conforme'];
    $climTiers->update($user);
}
//Remplissage du tableau récap des options
//Le $idsProduitsOptions ci-dessous est un tableau avec en clés des puissances BTU et en valeur les id des produits options
$idsProduitsOptions = valeurs_selection_extrafield('idserviceoptionparpuissance', 'devisclimatisation_climtiers');
foreach($listeClims as $clim) {
    //Chercher les produits option si longueur de liaison > 3M
    if (intval($clim["longueurdelaisonestimee"]) > 3) {
        $qte_pour_devis = intval($clim["longueurdelaisonestimee"]) - 3; //on met comme qté en option ce qui dépasse les 3m
        $produit = new Product($db);
        $produit->fetch($idsProduitsOptions[$clim["puissancebtu"]]);
        if (!empty($tab_options[$clim["puissancebtu"]])) {
            //Si on a déjà une ligne pour cette puissance BTU dans $tab_options, incrémenter la qté avec la longueur de liaison
            $tab_options[$clim["puissancebtu"]]["qte"] += $qte_pour_devis;
        } else {
            //Sinon, créer une ligne pour cette puissance BTU dans $tab_options
            $tab_options[$clim["puissancebtu"]] = array("idproduit" => $produit->id,
                                                        "tauxtva" => $produit->tva_tx,
                                                        "puht" => $produit->price,
                                                        "qte" => $qte_pour_devis,
                                                        "description" => $produit->label.'<br>'.$produit->description
                                                    );
        }
    } //if (intval($clim["longueurdelaisonestimee"]) > 3) {
} //foreach($listeClims as $clim) {
//** Création du devis des abonnements clims */
$devis = new Propal($db);
$devis->date = dol_now();
$devis->socid = $clientId;
$devis->ref_client = '';
$devis->cond_reglement_id = 6;
$devis->mode_reglement_id = 3;
$idDevis1 = $devis->create($user);
$devis->fetch($idDevis1); // rechargement nécessaire pour générer le PDF
foreach($tab_clims as $clim) {
    //Ajouter les lignes de devis des services abonnements clims
    $idLigne = $devis->addline(
        $clim["description"], //Description
        $clim["puht"], //Prix unitaire HT
        $clim["qte"], //Qty
        $clim["tauxtva"], //Taux TVA du service, qui n'est pas par défaut get_default_tva($clientId, $clientId)
        0, //Deprecated
        0, //Deprecated
        $clim["idservice"], //fk_product
        0,
        'HT',
        0,
        0,
        1 //On force type = service, contrairement à la doc de addline qui indique : Type of line (0=product, 1=service). Not used if fk_product is defined, the type of product is used.

    //Les autres caractéristiques de la ligne de devis prennent les valeurs par défaut
    );
} //FIN foreach($tab_clims as $clim) {
$devis->update($user);
$devis->fetch($idDevis1); //Recharger le devis pour que les détails du PDF soient complets
$devis->valid($user); //Valider le devis, pour lui affecter un numéro de référence
$outputlangs = $langs;
$OKpdf = $devis->generateDocument('', $outputlangs, 0, 0, 0); //Générer PDF avec modèle par défaut
$ref1 = $devis->ref;

if (!empty($tab_options) && count($tab_options > 0)) {
    //** Création du devis des options clims */
    $devis = new Propal($db);
    $devis->date = dol_now();
    $devis->socid = $clientId;
    $devis->ref_client = '';
    $devis->cond_reglement_id = 6;
    $devis->mode_reglement_id = 3;
    $idDevis2 = $devis->create($user);
    $devis->fetch($idDevis2); // rechargement nécessaire pour générer le PDF
    foreach($tab_options as $option) {
        //Ajouter les lignes de devis des services abonnements clims
        $idLigne = $devis->addline(
            $option["description"], //Description
            $option["puht"], //Prix unitaire HT
            $option["qte"], //Qty
            $option["tauxtva"], //Taux TVA du service, qui n'est pas par défaut get_default_tva($clientId, $clientId)
            0, //Deprecated
            0, //Deprecated
            $option["idproduit"] //fk_product
            //Les autres caractéristiques de la ligne de devis prennent les valeurs par défaut
        );
    } //FIN foreach($tab_options as $option) {
    $devis->update($user);
    $devis->fetch($idDevis2); //Recharger le devis pour que les détails du PDF soient complets
    $devis->valid($user); //Valider le devis, pour lui affecter un numéro de référence
    $outputlangs = $langs;
    $OKpdf = $devis->generateDocument('', $outputlangs, 0, 0, 0); //Générer PDF avec modèle par défaut
    $ref2 = $devis->ref;
    $message = 'Devis <a href="'.DOL_MAIN_URL_ROOT.'/comm/propal/card.php?id='.$idDevis1.'" target="_blank">'.$ref1.'</a> et <a href="'.DOL_MAIN_URL_ROOT.'/comm/propal/card.php?id='.$idDevis2.'" target="_blank">'.$ref2.'</a> créés.';
} else {
    //Si pas d'options
    $idDevis2 = 0;
    $message = 'Devis <a href="'.DOL_MAIN_URL_ROOT.'/comm/propal/card.php?id='.$idDevis1.'" target="_blank">'.$ref1.'</a> créé.';
} //if (!empty($tab_options)) {

http_response_code(201);
$retour = array('idpropal1' => $idDevis1, 'idpropal2' => $idDevis2, 'message' => $message);
print json_encode($retour);


