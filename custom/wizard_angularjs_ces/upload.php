<?php


$chemin = $conf->societe->dir_output.'/'.$clientId;


$defaultUploadPath = 'uploads';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!empty($_FILES['file']['name']) && !empty($_POST['destinationPath'])) {
		$destinationPath = !empty($_POST['destinationPath']) ? $defaultUploadPath.'/'.rtrim($_POST['destinationPath'], '/') . '/' : $defaultUploadPath;
		$fileName = basename($_FILES['file']['name']);
		$uploadPath = $destinationPath . $fileName;

		// Créer le dossier si nécessaire
		if (!is_dir($destinationPath)) {
			mkdir($destinationPath, 0777, true);
		}

		if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {
			echo json_encode(['filePath' => $uploadPath]);
		} else {
			http_response_code(500);
			echo json_encode(['error' => 'Erreur lors de l\'upload']);
		}
	} else {
		http_response_code(400);
		echo json_encode(['error' => 'Chemin ou fichier manquant']);
	}
}
