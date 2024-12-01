dolimoni.directive('fileUploader', function($http, $timeout) {
	return {
		restrict: 'E',
		scope: {
			destinationPath: '@?', // Chemin optionnel
			model: '=', // Modèle pour enregistrer le chemin du fichier
			renameFile: '@?' // Nouveau nom du fichier optionnel
		},
		template: `
            <div>
                <input type="file" />
                <p ng-if="uploadError" style="color: red;">Erreur: {{ uploadError }}</p>
                <p ng-if="uploadSuccess" style="color: green;">Fichier uploadé avec succès : {{ model }}</p>
            </div>
        `,
		link: function(scope, element, attrs) {
			scope.uploadSuccess = false;
			scope.uploadError = null;
			scope.selectedFile = null;

			scope.isMultiple = attrs.hasOwnProperty('multiple');

			// Récupérer l'élément input
			const inputElement = element.find('input');

			if (scope.isMultiple) {
				inputElement.attr('multiple', 'multiple');
			}

			// Liste des attributs AngularJS à exclure
			const angularAttributes = ['destinationPath', 'model', 'renameFile', '$attr'];

			// Appliquer les attributs valides à l'élément input
			angular.forEach(attrs, function(value, key) {
				if (!angularAttributes.includes(key) && !key.startsWith('$')) {
					inputElement.attr(key, value);
				}
			});

			// Écouteur pour détecter le changement de fichier
			inputElement.on('change', function(event) {
				const files = event.target.files;
				if (files.length > 0) {
					scope.uploadedFiles = []; // Réinitialise la liste des fichiers
					scope.uploadFiles(Array.from(files)); // Convertit en tableau et appelle la fonction d'upload
				}
			});

			function getFileExtension(filename) {
				return filename.split('.').pop().toLowerCase();
			}

			// Fonction pour uploader le fichier
			scope.uploadFiles = function(files) {
				files.forEach((file, index) => {
					const formData = new FormData();

					const newFileName = scope.renameFile ? `${scope.renameFile}.${file.name}` : file.name;

					// Ajouter le fichier avec le nom spécifié
					formData.append('file', file, newFileName);

					// Utiliser un chemin par défaut si `destinationPath` n'est pas défini
					const destinationPath = scope.destinationPath || '/uploads/default';
					formData.append('destinationPath', destinationPath);

					// Envoyer la requête HTTP pour chaque fichier
					$http.post('upload.php', formData, {
						headers: { 'Content-Type': undefined },
						transformRequest: angular.identity
					}).then(function(response) {
						$timeout(() => {
							scope.uploadedFiles.push({ name: newFileName, success: true });
							scope.model.push(response.data.filePath); // Ajouter le chemin au modèle
						});
					}).catch(function() {
						$timeout(() => {
							scope.uploadedFiles.push({ name: newFileName, success: false });
						});
					});
				});
			};
		}
	};
});
