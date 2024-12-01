<?php
header('Content-Type: text/html; charset=utf-8'); // Assure la réponse en UTF-8
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
 * \file htdocs/custom/devis/www/ajax_calcul_recap_devis_ces.php
 * \brief Retourne une chaine HTML pour affichage de la récapitulation des lignes et totaux d'un devis de clims
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

if (!defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL', '1'); // Disables token renewal
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
require_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';
dol_include_once('/caracredit/class/credit.class.php');

//Gestion financement
dol_include_once('/caracredit/lib/caracredit_credit.lib.php');
dol_include_once('/caracredit/class/creditclient.class.php');
dol_include_once('/caracredit/lib/caracredit_creditclient.lib.php');
//Gestion précarité
dol_include_once('/caraprime/class/precarite.class.php');
dol_include_once('/caraprime/lib/caraprime_precarite.lib.php');

dol_include_once('/devis/lib/devis.lib.php');


$entity = $_SESSION['dol_entity'];

$products = array();


$devis = $_POST['devis'];


$totalToPay = array(
	'total_ht'=>0,
	'total_tva'=>0,
	'total_ttc'=>0,
);

foreach ($devis['products'] as $key => $product) {

	$product['ht'] = 0;
	$product['tva'] = 0;
	$dolibarProduct = new Product($db);
	if (! $dolibarProduct->fetch('', $product['name'])) {
		throw new Exception("Produit {$product['name']} introuvable. Veuillez vérifier les références.");
	}

	$product['description'] = $dolibarProduct->ref;
	$product['pu'] = number_format($dolibarProduct->price,'2','.');
	$product['ht'] += $dolibarProduct->price * $product['quantity'];
	$product['tva'] += $devis['products']['ht'] * $dolibarProduct->tva_tx / 100;

	$totalToPay['total_ht']+=$product['ht'];
	$totalToPay['total_tva']+=$product['tva'];
	$totalToPay['total_ttc']+=$product['ht']+$product['tva'];

	$products[] = $product;
}

$response = array(
	'status'=>'success',
	'products'=>$products,
	'totalToPay'=>$totalToPay,
);


echo json_encode($response);
