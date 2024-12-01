/* 
 * Js for zpocscan.php
 */

const webcamElement = document.getElementById('webcam');
const canvasElement = document.getElementById('canvas');
const snapSoundElement = document.getElementById('snapSound');

const webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);
var tabProjRefVal = [];
var tabTiersRefVal = []; // Ajout pour gérer les tiers
var nbCam = 0;
var file2upl = false;
var photoTaken = false;
var picture = ''; // Stocker l'image capturée en base64

var wcOn = -1;
webcam.start()
	.then(result => {
		wcOn = 1;
		console.log("Webcam démarrée");
		nbCam = webcam.webcamCount;
	})
	.catch(err => {
		alert('Impossible de démarrer la caméra : ' + err);
		console.log(err);
	});

$(document).ready(function () {
	let wcwidth = $('#webcam').parent().width();
	if (wcwidth > 640) wcwidth = 640;
	$('#webcam').width(wcwidth);
	$('#webcam').height($('#webcam').width() * 3 / 4);
	toggleSubmit(false);

	$('#startscan').click(function () {
		toggleCam();
	});

	$('#sendButton').click(function () {
		if (photoTaken) {
			// Envoi de l'image capturée pour le projet ou le tiers sélectionné
			if ($("#refproject").val() != '') {
				postImage('project', $('#refproject').val(), picture, dolToken);
			} else if ($("#reftiers").val() != '') {
				postImage('tiers', $('#reftiers').val(), picture, dolToken); // Ajout de la gestion des tiers
			}
			camStart();
		}
		if (file2upl) {
			$('#file-upl').show();
		} else return false;
	});

	$('#btflip').click(function () {
		webcam.stop();
		webcam.flip();
		webcam.start();
	});

	// Gestion du champ projet (réinitialisation et datalist)
	$("#clearrefproject").click(function () {
		$("#refproject").val("");
		$('#projectlabel').html('');
		toggleSubmit(false);
		return false;
	});

	$('#refproject').on('input', function () {
		if (!projSetLabel()) ajaxGetDataList('project', 'refproject', $('#refproject').val(), dolToken);
		checkSubmitOk();
	});

	// Gestion du champ tiers (réinitialisation et datalist)
	$("#clearreftiers").click(function () {
		$("#reftiers").val("");
		$('#tierslabel').html('');
		toggleSubmit(false);
		return false;
	});

	$('#reftiers').on('input', function () {
		if (!tiersSetLabel()) ajaxGetDataList('tiers', 'reftiers', $('#reftiers').val(), dolToken); // Recherche des tiers
		checkSubmitOk();
	});

	$("#send-file-btn").click(function () {
		if ($("#refproject").val() != '' || $("#reftiers").val() != '') {
			$('#file-upl').trigger("click");
			file2upl = true;
			checkSubmitOk();
		} else {
			alert("Veuillez sélectionner un projet ou un tiers avant d'importer un fichier.");
		}
	});
});

// Gérer la capture d'image via la webcam
function toggleCam() {
	if (wcOn == 1) {
		picture = webcam.snap(); // Capture l'image en base64
		webcam.stop();
		$('#startscan').html('<i class="fa-solid fa-video xxx-large"></i>');
		wcOn = 0;
		photoTaken = true;
		checkSubmitOk();
	} else if (wcOn == 0) {
		camStart();
	}
}

function camStart() {
	webcam.start();
	$('#startscan').html('<i class="fa-solid fa-camera xxx-large"></i>');
	wcOn = 1;
	photoTaken = false;
	checkSubmitOk();
}

// Gérer l'affichage des labels pour les projets
function projSetLabel() {
	for (var p of tabProjRefVal) {
		if (p.value == $('#refproject').val()) {
			$('#projectlabel').html(p.label.replace(p.value, ''));
			return 1;
		}
	}
	return 0;
}

// Gérer l'affichage des labels pour les tiers
function tiersSetLabel() {
	for (var t of tabTiersRefVal) {
		if (t.value == $('#reftiers').val()) {
			$('#tierslabel').html(t.label.replace(t.value, ''));
			return 1;
		}
	}
	return 0;
}

// Vérification avant la soumission du formulaire
function checkSubmitOk() {
	toggleSubmit(($("#refproject").val() != '' || $("#reftiers").val() != '') && (photoTaken || file2upl));
}

// Activation/désactivation du bouton de soumission
function toggleSubmit(bool) {
	$('#sendButton').prop('disabled', !bool);
}

// Fonction AJAX pour récupérer les projets ou les tiers depuis ajaxDataList.php
function ajaxGetDataList(type, inputId, inputValue, token) {
	$.ajax({
		url: 'ajaxDataList.php',
		method: 'POST',
		data: {
			token: token,
			type: type,  // Peut être 'project' ou 'tiers'
			search: inputValue
		},
		success: function (data) {
			var result = JSON.parse(data);
			if (type == 'project') {
				tabProjRefVal = result;
				projSetLabel();
			} else if (type == 'tiers') {
				tabTiersRefVal = result;
				tiersSetLabel();
			}
		},
		error: function (xhr, status, error) {
			console.error("Erreur AJAX: ", status, error);
		}
	});
}

// Fonction pour poster l'image capturée à ajaxDataPost.php
function postImage(type, id, base64Image, token) {
	$.ajax({
		url: 'ajaxDataPost.php',
		method: 'POST',
		data: {
			token: token,  // Inclure le token CSRF
			objtype: type,  // Peut être 'project' ou 'tiers'
			id: id,
			rawfile: base64Image,
			name: ''  // Si un nom spécifique est nécessaire, vous pouvez le définir ici
		},
		success: function (response) {
			alert(response);  // Afficher la réponse du serveur (succès ou échec)
		},
		error: function (xhr, status, error) {
			console.error("Erreur lors de l'envoi de l'image: ", status, error);
		}
	});
}
