<?php

/*
 * Copyright (C) 20xx VMA Vincent Maury <vmaury@timgroup.fr>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY
 */

include './config.inc.php';
// If user is connected
if (empty($_SESSION['dol_login'])) {
	die('Unconnected');
} else {
	/* ça ça marche pas :-\
	 * if (GETPOST('token') != $_SESSION['token']) {
		die ('Invalid Token');
	} */
	//echo GETPOST("type").'/';
	//echo GETPOST("search");
	$search = addslashes(GETPOST("search"));
	$tbresult = [];
	switch(GETPOST("type")) {
		case 'product':
			// Récupération des produits
			$form = new Form($db);
			$tbresult = $form->select_produits_list('', 'productid', '', 20, 0, $search, -1, 2, 1, 0, '1');
			break;

		case 'project':
			// Récupération des projets
			require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';
			$form = new FormProjets($db);
			$tbresult = $form->select_projects_list(-1, '', 'projectid', 24, 0, 1, 1, 0, 0, 1, $search);
			break;

		case 'tiers':  // Ajout pour la gestion des tiers
			// Récupération des tiers (clients)
			require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
			$societe = new Societe($db);
			$sql = "SELECT rowid, nom FROM ".MAIN_DB_PREFIX."societe WHERE nom LIKE '%".$search."%' AND entity = ".$conf->entity." LIMIT 20";
			$resql = $db->query($sql);

			if ($resql) {
				while ($obj = $db->fetch_object($resql)) {
					$tbresult[] = array('value' => $obj->rowid, 'label' => $obj->nom);
				}
			}
			break;

		case 'user':
			// Récupération des utilisateurs
			$form = new Form($db);
			$morefilter = " AND (lastname LIKE '%".$search."%' OR firstname LIKE '%".$search."%' OR login LIKE '%".$search."%')";
			$tbresbrut = $form->select_dolusers('', 'userid', 0, null, 0, '', '', '0', 0, -1, $morefilter, 0, '', 0, 1, false);
			$userStat = new User($db);
			foreach ($tbresbrut as $id => $name) {
				$userStat->fetch($id);
				$name = str_ireplace('Actif ', '', $name);
				$tbresult[] = array('value' => $userStat->login, 'label' => $name);
			}
			break;

		default:
			die('Unknown type');
	}

	echo json_encode($tbresult);
}
