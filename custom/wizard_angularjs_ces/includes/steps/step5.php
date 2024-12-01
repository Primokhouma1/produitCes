<div ng-if="currentStep===5" id="step5" class="step w-50">
	<!-- Contact pour le financement -->
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >Contact pour le financement</legend>
		<!-- Checkbox pour afficher/masquer le contact supplémentaire -->
		<div class="field">
			<label for="showFinanceContact">Ajouter une personne ?</label>
			<input ng-model="devis.finance.showFinanceContact" type="checkbox" id="showFinanceContact" name="showFinanceContact">
		</div>
		<!-- Bloc de champs pour le contact supplémentaire (initialement masqué) -->
		<div ng-if="devis.finance.showFinanceContact" id="financeContactFields">
			<div class="field">
				<label for="financeContactFullname">Nom et prénom du contact :</label>
				<input ng-model="devis.finance.financeContactFullname" class="form-control" type="text" id="financeContactFullname" name="financeContactFullname">
			</div>
			<div class="field">
				<label for="financeContactRelation">Fonction ou parenté :</label>
				<input ng-model="devis.finance.financeContactRelation" class="form-control" type="text" id="financeContactRelation" name="financeContactRelation">
			</div>
			<div class="field">
				<label for="financeContactMobile">Téléphone portable du contact :</label>
				<input ng-model="devis.finance.financeContactMobile" class="form-control" type="tel" id="financeContactMobile" name="financeContactMobile">
			</div>
		</div>
	</fieldset>
	<br>
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >Mode de paiement</legend>
		<div ng-init="devis.finance.paymentMode.type='extra'" class="payment-option-container">
			<label for="cheque">Par Cheque</label>  <!-- 5X max pour CES, ITE -->
			<input ng-model="devis.finance.paymentMode.value" type="radio" id="cheque" name="paymentMode" value="cheque" onclick="togglePaymentOption()">
			<label for="virement">Virement</label>
			<input ng-model="devis.finance.paymentMode.value" type="radio" id="virement" name="paymentMode" value="virement" onclick="togglePaymentOption()">
			<label for="credit">Prélèvement</label><!-- Choisir l'échéance soit le 5, 10 ou le 30 -->
			<input ng-model="devis.finance.paymentMode.value" type="radio" id="prelevement" name="paymentMode" value="prelevement"
				   onclick="togglePaymentOption()">
			<label for="credit">Crédit</label> <!-- Faire communiquer avec CARA CREDIT -->
			<input ng-model="devis.finance.paymentMode.value" type="radio" id="credit" name="paymentMode" value="credit" onclick="togglePaymentOption()">

		</div>
	</fieldset>
	<br>
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >Modalités de règlement</legend>
		<div class="field">
			<label for="paymentTerms">Choisissez les conditions :</label>
			<select
				ng-options="paymentTerms.key as paymentTerms.value for paymentTerms in paymentTermsOptions"
				class="form-select"
				ng-init="devis.finance.paymentTerms.type='extra'"
				ng-model="devis.finance.paymentTerms.value"
				id="paymentTerms"
				name="paymentTerms">
			</select>
		</div>
	</fieldset>
	<br>
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >Acompte</legend>
		<div class="deposit-container">
			<label for="deposit">Versement d'un acompte ?</label>
			<input ng-model="devis.finance.hasDeposit" type="checkbox" id="deposit" name="deposit">
		</div>
		<div ng-if="devis.finance.hasDeposit" id="depositFields" >
			<div class="field">
				<label for="depositAmount">Montant de l'acompte :</label>
				<input ng-init="devis.finance.depositAmount.type='extra'" ng-model="devis.finance.depositAmount.value" class="form-control" type="text" id="depositAmount" name="depositAmount">
			</div>
		</div>
	</fieldset>
	<br>
	<div class="button-container">
		<button class="btn btn-secondary" ng-click="previousStep()">Précédent</button>
		<button class="btn btn-info" ng-click="calculateCESProducts();nextStep()">Suivant</button>
	</div>

</div>
