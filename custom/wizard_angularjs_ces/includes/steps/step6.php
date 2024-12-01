<div ng-if="currentStep===6" id="step6" class="step w-50" ng-class="currentStep===6 ? 'w-100' : ''">
	<br>

	<!-- Le message de félicitations sera affiché ici -->
	<div id="congratulations-message"></div>
	<br>
	<br>
	<br>
	<fieldset class="border rounded-3 p-3">
		<br>
		<legend class="float-none w-auto px-3" >Informations sur le bon de commande</legend>
		<!-- Ajout du tableau BC-->
		<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>TVA</th>
                    <th>P.U. HT</th>
                    <th>Qté</th>
                    <th>Total HT</th>
                </tr>
            </thead>
            <tbody>
                <!-- Lignes des produits -->
                <tr ng-if="product.quantity > 0" ng-repeat="product in devis.produits">
                    <td>{{product.description}}</td>
                    <td>{{product.tva * 100}}%</td>
                    <td>{{product.pu.toFixed(2)}}€</td>
                    <td><input disabled ng-change="updateTotalToPay()" class="form-control" ng-model="product.quantity"></td>
                    <td>{{(product.pu * product.quantity).toFixed(2)}}€</td>
                </tr>
            </tbody>
        </table>
		<!-- Totals section -->
		<div class="totals-container" style="text-align:right; margin-top:20px;">
            <div class="total-item">
                <strong>Total HT:</strong> <span id="total-ht-value">{{devis.totalToPay.total_ht}}€</span>
            </div>
            <div class="total-item">
                <strong>Total TVA:</strong> <span id="total-tva-value">{{devis.totalToPay.total_tva}}€</span>
            </div>
            <div class="total-item">
                <strong>Total TTC:</strong> <span id="total-ttc-value">{{devis.totalToPay.total_ttc}}€</span>
            </div>
            <div class="total-item">
                <strong>R.A.C :</strong> <span id="total-ttc-value">{{devis.totalToPay.rac}}€</span>
            </div>
            <div class="total-item">
                <strong>Mensualité :</strong> <span id="total-ttc-value">{{devis.totalToPay.monthlyPayment}}€</span>
            </div>
        </div>
	</fieldset>
	<script>
		// Récupérer l'élément input par son ID
		var inputFullname = document.getElementById('fullname');

		// Récupérer l'élément span par son ID
		var fullnameDisplay = document.getElementById('fullname-display');

		// Écouter l'événement de changement (par exemple, lorsque l'utilisateur tape quelque chose)
		inputFullname.addEventListener('input', function () {
			// Récupérer la valeur de l'input et l'afficher dans la console
			var fullnameValue = inputFullname.value;
			console.log(fullnameValue);

			// Mettre à jour le contenu de la balise span avec le nom complet
			fullnameDisplay.textContent = fullnameValue;
		});
	</script>
	<br>

	<div class="button-container">
		<button class="btn btn-secondary" ng-click="previousStep()">Précédent</button>
		<button class="btn btn-success" ng-click="add()">Créer le devis</button>
		<button class="new-quote-button btn btn-danger" onclick="resetForm()">Réinitialiser</button>
	</div>
</div>
