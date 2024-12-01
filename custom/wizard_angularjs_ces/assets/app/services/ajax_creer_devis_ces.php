<?php
header('Content-Type: application/json; charset=utf-8');


// Désactivation de la mise en cache


header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');


// Headers CORS si nécessaire
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Headers pour la réponse JSON
header('Content-Type: application/json; charset=utf-8');


// Activez l'affichage des erreurs pour détecter les problèmes.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);


/* Copyright (C) 2013		Laurent LOUIS-THERESE		<laurent@bcsirt.fr>
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
 * \file htdocs/custom/devisces/www/ajax_creer_devis_ces.php
 * \brief à partir d'une soumission asynchrone de formulaire, crée une fiche Tiers, lui ajoute des documents lié ainsi que des ces liés, et enfin crée un devis
 */


if (empty($noIncMain)) {
	include_once 'includes/0inc.php';
	//$resultFetchUser=$user->fetch('', 'vmaury', '', 1, ($entitytotest > 0 ? $entitytotest : -1));
} else include_once 'includes/config.inc.php';
if (multiCompany) {
	include_once DOL_DOCUMENT_ROOT . '/custom/multicompany/class/actions_multicompany.class.php';
	$actionMC = new ActionsMulticompany($db);
}


if (!defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL', 1); // Disables token renewal
if (!defined('NOREQUIREMENU')) define('NOREQUIREMENU', '1');
if (!defined('NOREQUIREHTML')) define('NOREQUIREHTML', '1');
if (!defined('NOREQUIREAJAX')) define('NOREQUIREAJAX', '1');
if (!defined('NOREQUIRESOC')) define('NOREQUIRESOC', '1');
if (!defined('NOCSRFCHECK')) define('NOCSRFCHECK', '1');
if (empty($_GET['keysearch']) && !defined('NOREQUIREHTML')) define('NOREQUIREHTML', '1');

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"] . "/main.inc.php";
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME'];
$tmp2 = realpath(__FILE__);
$i = strlen($tmp) - 1;
$j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
	$i--;
	$j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1)) . "/main.inc.php")) $res = @include substr($tmp, 0, ($i + 1)) . "/main.inc.php";
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1))) . "/main.inc.php")) $res = @include dirname(substr($tmp, 0, ($i + 1))) . "/main.inc.php";
// Try main.inc.php using relative path
if (!$res && file_exists("../main.inc.php")) $res = @include "../main.inc.php";
if (!$res && file_exists("../../main.inc.php")) $res = @include "../../main.inc.php";
if (!$res && file_exists("../../../main.inc.php")) $res = @include "../../../main.inc.php";
if (!$res) die("Include of main fails");
require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT . '/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT . '/comm/propal/class/propal.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/propal.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';
//dol_include_once('/devis/www/class/DataValidator.php');
//dol_include_once('/devis/www/class/ApiResponse.php');
//dol_include_once('/devis/www/class/FileHandler.php');
//dol_include_once('/devis/www/class/Logger.php');

//use FileHandler\FileHandler;
//use Logger\Logger;

$entity = $_SESSION['dol_entity'];

$id_rdv = GETPOST('id_rdv', 'int'); // Récupération de l'ID du rendez-vous

// Fonction de gestion des erreurs
$logger = new Logger(LOG_FILE);
$fileHandler = new FileHandler();

set_error_handler("exception_error_handler");

// Configuration du logging
const LOG_FILE = __DIR__ . '/devis_ces_debug.log';

function writeLog($message, $data = null)
{
	$logMessage = date('Y-m-d H:i:s') . " - " . $message;
	if ($data !== null) {
		$logMessage .= "\n" . print_r($data, true);
	}
	file_put_contents(LOG_FILE, $logMessage . "\n", FILE_APPEND);
}

// Fonction pour envoyer une réponse JSON
function sendJsonResponse($data, $statusCode = 200)
{
	http_response_code($statusCode);
	echo json_encode($data);
	exit;
}


// Vérification de l'authentification de l'utilisateur
if (empty($user) || empty($user->id)) {
	// Charger un utilisateur par défaut ou gérer l'authentification
	sendJsonResponse([
		'success' => false,
		'message' => 'Utilisateur non authentifié. Veuillez vous connecter.'
	], 401);
}

try {
	writeLog("Début du script");

	// Récupération des données depuis $_POST
	if (empty($_POST)) {
		throw new Exception("Aucune donnée reçue");
	}

	writeLog("Contenu de \$_POST :", $_POST);
	writeLog("Contenu de \$_FILES :", $_FILES);


	// Récupération des données depuis $_POST
	$cesData = isset($_POST['cesData']) ? json_decode($_POST['cesData'], true) : null;

	writeLog("Données CES reçues", $cesData);

	if (json_last_error() !== JSON_ERROR_NONE) {
		throw new Exception("Erreur de décodage JSON: " . json_last_error_msg());
	}

	// Vérification du format des données
	if (!is_array($cesData) || count($cesData) === 0) {
		throw new Exception("Format de données invalide");
	}

	writeLog("Après validation des données");


	//***** Etape 1 : créer la fiche Tiers *******/
	writeLog("Avant la création du prospect");
	$prospect = new Societe($db);
	$prospect->entity = $conf->entity;
	if (GETPOST('clientType', 'alpha') == 'individual') {
		//Si prospect type Particulier
		$prospect->typent_id = 8; //Type de Tiers = Particulier
		$prospect->name = trim(GETPOST('fullname', 'alpha'));
		$prospect->phone = trim(GETPOST('phone', 'alpha'));
		$prospect->fax = trim(GETPOST('phonemobile', 'alpha')); //Téléphone mobile dans FAX
		$prospect->email = trim(GETPOST('email', 'alpha'));
		$prospect->address = trim(GETPOST('address', 'alpha'));
		$prospect->zip = trim(GETPOST('postcode', 'alpha'));
		$prospect->town = trim(GETPOST('city', 'alpha'));
		$prospect->fk_departement = 296;
		$prospect->fk_pays = 1;
		$prospect->cond_reglement_id = 6;
		$prospect->mode_reglement_id = 3;
		//TODO
	} else {
		//Si prospect type Société
		$prospect->typent_id = 3; //Type de Tiers = PME/PMI
		$prospect->name = trim(GETPOST('companyName', 'alpha'));
		$prospect->idprof2 = trim(GETPOST('siret', 'alpha')); //SIRET
		$prospect->phone = trim(GETPOST('companyPhone', 'alpha'));
		$prospect->email = trim(GETPOST('companyEmail', 'alpha'));
		$prospect->address = trim(GETPOST('companyAddress', 'alpha'));
		$prospect->zip = trim(GETPOST('companyPostcode', 'alpha'));
		$prospect->town = trim(GETPOST('companyCity', 'alpha'));
		$prospect->fk_departement = 296;
		$prospect->fk_pays = 1;
		$prospect->cond_reglement_id = 6;
		$prospect->mode_reglement_id = 3;
		//TODO
	} //FIN if ( GETPOST('clientType', 'alpha') == 'individual') {
	$prospect->client = 2; //0=rien , 1=client, 2=prospect, 3=client et prospect
	$prospect->code_client = -1; //Signifie que le code client sera généré automatiquement
	$prospect->fournisseur = 0;
	$OKClient = $prospect->create($user);
	if ($OKClient < 0) {
		http_response_code(500);
		print "Erreur en création du prospect";
	} else {
		//Affecter l'utilisateur comme commercial du client
		$prospect->add_commercial($user, $user->id);
		//MAJ des extrafields client
		$prospect->fetch($OKClient);
		$prospect->array_options['options_type_occupant'] = trim(GETPOST('ownership', 'alpha'));
		$prospect->array_options['options_latitude'] = trim(GETPOST('latitude', 'alpha'));
		$prospect->array_options['options_longitude'] = trim(GETPOST('longitude', 'alpha'));
		$prospect->array_options['options_numero_edl'] = trim(GETPOST('edf-number', 'alpha'));
		$prospect->array_options['options_numero_contrat_edf'] = trim(GETPOST('contact-number', 'alpha'));
		$prospect->array_options['options_typedelogement'] = trim(GETPOST('housingType', 'alpha'));
		$prospect->array_options['options_etages'] = trim(GETPOST('building-stories', 'alpha'));
		$prospect->array_options['options_nb_piece_logement'] = trim(GETPOST('room-number', 'alpha'));
		$prospect->array_options['options_echeance_prelevement'] = trim(GETPOST('echeance_prelevement', 'alpha'));
		$prospect->array_options['options_etatop'] = 10;
		//$prospect->array_options['options_etatfinancement'] = 1;
		$prospect->array_options['options_previsite'] = 1;
		if (GETPOST('clientType', 'alpha') == 'individual') {
			//En attente, si extrafields propres aux Particuliers
		} else {
			//Si prospect type Société
			$prospect->array_options['options_secteurdactivite'] = trim(GETPOST('industry', 'alpha'));
			//on crée un contact pour cette société
			$contact = new Contact($db);
			$contact->socid = $OKClient;
			$contact->firstname = trim(GETPOST('contactFirstName', 'alpha'));
			$contact->lastname = trim(GETPOST('contactLastName', 'alpha'));
			$contact->poste = trim(GETPOST('position', 'alpha'));
			$contact->email = trim(GETPOST('companyEmail', 'alpha'));
			$contact->phone_pro = trim(GETPOST('companyPhone', 'alpha'));
			$contact->address = trim(GETPOST('address', 'alpha'));
			$contact->zip = trim(GETPOST('postcode', 'alpha'));
			$contact->town = trim(GETPOST('city', 'alpha'));
			$contact->fk_departement = 296;
			$contact->fk_pays = 1;
			$OKContact = $contact->create($user);
		}  //FIN if ( GETPOST('clientType', 'alpha') == 'individual') {
		$prospect->update($OKClient, $user);  //MAJ du prospect avec les extrafields remplis
	}  //FIN if ($OKClient < 0) {

	//Ajouter les fichiers téléchargés au Tiers
	$chemin = $conf->societe->dir_output . '/' . $OKClient;
	//Créer le répertoire des documents du Tiers, si il n'existe pas encore
	if (!is_dir($chemin)) {
		mkdir($chemin);
	}
	//Chargement des documents d'un locataire, le cas échéant
	if (trim(GETPOST('ownership', 'alpha')) == "2") { //2 => locataire
		//Chargement d'une autorisation
		if (isset($_FILES['authorization']['tmp_name'])) {
			if ($_FILES['authorization']['name'] != "") {
				$nom_fichier_tmp = $_FILES['authorization']['tmp_name'];
				copy($nom_fichier_tmp, $chemin . '/Une autorisation.' . pathinfo($_FILES['authorization']['name'], PATHINFO_EXTENSION));
			}
		}
		//Chargement NI Propriétaire recto
		if (isset($_FILES['owner-id-recto']['tmp_name'])) {
			if ($_FILES['owner-id-recto']['name'] != "") {
				$nom_fichier_tmp = $_FILES['owner-id-recto']['tmp_name'];
				copy($nom_fichier_tmp, $chemin . '/La pièce d\'identité du propriétaire ( recto ).' . pathinfo($_FILES['owner-id-recto']['name'], PATHINFO_EXTENSION));
			}
		}
		//Chargement NI Propriétaire verso
		if (isset($_FILES['owner-id-verso']['tmp_name'])) {
			if ($_FILES['owner-id-verso']['name'] != "") {
				$nom_fichier_tmp = $_FILES['owner-id-verso']['tmp_name'];
				copy($nom_fichier_tmp, $chemin . '/La pièce d\'identité du propriétaire ( verso ).' . pathinfo($_FILES['owner-id-verso']['name'], PATHINFO_EXTENSION));
			}
		}
		//Chargement NI Propriétaire recto
		if (isset($_FILES['tenantslease']['tmp_name'])) {
			if ($_FILES['tenantslease']['name'] != "") {
				$nom_fichier_tmp = $_FILES['tenantslease']['tmp_name'];
				copy($nom_fichier_tmp, $chemin . '/Le bail de location.' . pathinfo($_FILES['tenantslease']['name'], PATHINFO_EXTENSION));
			}
		}
	} //FIN if (trim(GETPOST('ownership', 'alpha') == "2") { //2 => locataire

	//Chargement PASSEPORT(step2)
	if (isset($_FILES['passeport']['tmp_name'])) {
		if ($_FILES['passeport']['name'] != "") {
			$nom_fichier_tmp = $_FILES['passeport']['tmp_name'];
			copy($nom_fichier_tmp, $chemin . '/Passeport.' . pathinfo($_FILES['passeport']['name'], PATHINFO_EXTENSION));
		}
	}

	//Chargement CNI occupant recto (step2)
	if (isset($_FILES['identity-front']['tmp_name'])) {
		if ($_FILES['identity-front']['name'] != "") {
			$nom_fichier_tmp = $_FILES['identity-front']['tmp_name'];
			copy($nom_fichier_tmp, $chemin . '/Pièce d\'identité (recto).' . pathinfo($_FILES['identity-front']['name'], PATHINFO_EXTENSION));
		}
	}
	//Chargement CNI occupant verso (step2)
	if (isset($_FILES['identity-back']['tmp_name'])) {
		if ($_FILES['identity-back']['name'] != "") {
			$nom_fichier_tmp = $_FILES['identity-back']['tmp_name'];
			copy($nom_fichier_tmp, $chemin . '/Pièce d\'identité (verso).' . pathinfo($_FILES['identity-back']['name'], PATHINFO_EXTENSION));
		}
	}
	//Chargement Facture EDF de moins de 3 mois (step2)
	if (isset($_FILES['bill']['tmp_name'])) {
		if ($_FILES['bill']['name'] != "") {
			$nom_fichier_tmp = $_FILES['bill']['tmp_name'];
			copy($nom_fichier_tmp, $chemin . '/Facture EDF de moins de 3 mois.' . pathinfo($_FILES['bill']['name'], PATHINFO_EXTENSION));
		}
	}
	//Chargement AVIS IMPOT VOLET 1 (step2)
	if (isset($_FILES['tax-notice']['tmp_name'])) {
		if ($_FILES['tax-notice']['name'] != "") {
			$nom_fichier_tmp = $_FILES['tax-notice']['tmp_name'];
			copy($nom_fichier_tmp, $chemin . '/Avis d\'impôt N-1 sur les revenus.' . pathinfo($_FILES['tax-notice']['name'], PATHINFO_EXTENSION));
		}
	}
	//Chargement AVIS IMPOT VOLET 2 (step2)
	if (isset($_FILES['tax-notice-2']['tmp_name'])) {
		if ($_FILES['tax-notice-2']['name'] != "") {
			$nom_fichier_tmp = $_FILES['tax-notice-2']['tmp_name'];
			copy($nom_fichier_tmp, $chemin . '/Avis d\'impôt N-1 sur les revenus volet 2.' . pathinfo($_FILES['tax-notice-2']['name'], PATHINFO_EXTENSION));
		}
	}

	//Chargement AVIS IMPOT VOLET 3 (step2)
	if (isset($_FILES['tax-notice-3']['tmp_name'])) {
		if ($_FILES['tax-notice-3']['name'] != "") {
			$nom_fichier_tmp = $_FILES['tax-notice-3']['tmp_name'];
			copy($nom_fichier_tmp, $chemin . '/Avis d\'impôt N-1 sur les revenus volet 3.' . pathinfo($_FILES['tax-notice-3']['name'], PATHINFO_EXTENSION));
		}
	}
	//Chargement RIB (step2)
	if (isset($_FILES['rib']['tmp_name'])) {
		if ($_FILES['rib']['name'] != "") {
			$nom_fichier_tmp = $_FILES['rib']['tmp_name'];
			copy($nom_fichier_tmp, $chemin . '/RIB.' . pathinfo($_FILES['rib']['name'], PATHINFO_EXTENSION));
		}
	}

	// Création du devis
	$devis = new Propal($db);
	$devis->date = dol_now();
	$devis->socid = $OKClient;
	$devis->cond_reglement_id = 6;
	$devis->mode_reglement_id = 3;

	writeLog("Avant création du devis");

	$idDevis = $devis->create($user);
	if ($idDevis < 0) {
		throw new Exception("Erreur création devis: " . $devis->error);
	}

	writeLog("Devis créé avec ID", $idDevis);

	// Traitement des produits
	foreach ($cesData as $ces) {
		writeLog("Traitement CES", $ces);

		// Validation des données CES
		if (empty($ces['choixVolume'])) {
			throw new Exception("Volume CES non spécifié");
		}

		// Ajout du produit principal
		$refProduit = 'CES-' . $ces['choixVolume'];
		$product = new Product($db);

		if ($product->fetch('', $refProduit) <= 0) {
			throw new Exception("Produit non trouvé: " . $refProduit);
		}

		$result = $devis->addline(
			$product->description,
			$product->price,
			1,
			$product->tva_tx,
			0,
			0,
			$product->id
		);

		if ($result < 0) {
			throw new Exception("Erreur ajout ligne devis: " . $devis->error);
		}

		writeLog("Produit principal ajouté", $refProduit);
	}

	// Validation du devis
	$devis->fetch($idDevis);
	$result = $devis->valid($user);

	if ($result < 0) {
		throw new Exception("Erreur validation devis: " . $devis->error);
	}

	// Génération PDF
	$outputlangs = $langs;
	$result = $devis->generateDocument('', $outputlangs);

	if ($result < 0) {
		throw new Exception("Erreur génération PDF: " . $devis->error);
	}

	// Réponse succès
	sendJsonResponse([
		'success' => true,
		'message' => 'Devis créé avec succès',
		'data' => [
			'id' => $idDevis,
			'ref' => $devis->ref,
			'url' => DOL_URL_ROOT . '/comm/propal/card.php?id=' . $idDevis
		]
	]);

} catch (Exception $e) {
	// Capturer les sorties avant d'envoyer la réponse d'erreur
	$output = ob_get_clean();
	if (!empty($output)) {
		writeLog("Sortie inattendue détectée :", $output);
	}

	writeLog("Erreur", [
		'message' => $e->getMessage(),
		'trace' => $e->getTraceAsString()
	]);

	sendJsonResponse([
		'success' => false,
		'message' => $e->getMessage()
	], 500);
}



