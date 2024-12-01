<div ng-if="currentStep===2" id="step2" class="step w-50">
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >Pièces justificatives</legend>
		<div class="field">
			<label for="passeport">Passeport ( Si pas de CNI ) :</label>

			<file-uploader
				model="devis.customer.docs.passeport"
				destination-path="{{devis.tmp}}/customer"
				rename-file="Passport"
				class="form-control"
				id="passeport"
				name="passeport"
				accept="image/*,.pdf"
				capture="environment">
			</file-uploader>
		</div>
		<div class="field">
			<label for="identity-front">Pièce d'identité (recto) :</label>
			<file-uploader
				model="devis.customer.docs.identityFront"
				destination-path="{{devis.tmp}}/customer"
				rename-file="Pièce d'identité (recto)"
				class="form-control"
				accept="image/*,.pdf"
				capture="environment">
			</file-uploader>
		</div>
		<div class="field">
			<label for="identity-back">Pièce d'identité (verso) :</label>
			<file-uploader
				model="devis.customer.docs.identityBack"
				destination-path="{{devis.tmp}}/customer"
				rename-file="Pièce d'identité (verso)"
				class="form-control"
				accept="image/*,.pdf"
				capture="environment">
			</file-uploader>
		</div>
		<div class="field">
			<label for="bill">Facture EDF de moins de 3 mois :</label>
			<file-uploader
				model="devis.customer.docs.bill"
				destination-path="{{devis.tmp}}/customer"
				rename-file="Facture EDF de moins de 3 mois"
				class="form-control"
				accept="image/*,.pdf"
				capture="environment">
			</file-uploader>
		</div>
		<!--   Version avec l'upload multiple AI -->
		<div class="field">
			<label for="tax-notice">Avis d'impôt N-1 ( Les 3 volets ) :</label>
			<file-uploader
				model="devis.customer.docs.taxNotice"
				destination-path="{{devis.tmp}}/customer"
				rename-file="Avis d'impôt"
				class="form-control"
				accept="image/*,.pdf"
				multiple
				capture="environment">
			</file-uploader>
		</div>
		<!--   Version avec l'upload multiple -->
		<div class="field">
			<label for="title-deed">Titre de propriété :</label>
			<file-uploader
				model="devis.customer.docs.titleDeed"
				destination-path="{{devis.tmp}}/customer"
				rename-file="Titre de propriété"
				class="form-control"
				accept="image/*,.pdf"
				multiple
				capture="environment">
			</file-uploader>
		</div>
		<div class="field">
			<label for="property-tax">Taxe foncière :</label>
			<file-uploader
				model="devis.customer.docs.propertyTax"
				destination-path="{{devis.tmp}}/customer"
				rename-file="Taxe foncière"
				class="form-control"
				accept="image/*,.pdf"
				multiple
				capture="environment">
			</file-uploader>
		</div>
		<div class="file-list" id="file-list-container"></div>
		<div class="field">
			<label for="rib">RIB :</label>
			<file-uploader
				model="devis.customer.docs.RIB"
				destination-path="{{devis.tmp}}/customer"
				rename-file="RIB"
				class="form-control"
				accept="image/*,.pdf"
				multiple
				capture="environment">
			</file-uploader>
		</div>
	</fieldset>
	<br>
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >EDF</legend>
		<!-- Checkbox et bloc de champs pour la différence de nom sur la facture -->
		<div class="field" data-toggle-id="differentNameEDFFields">
			<label for="differentNameEDF">La facture est-elle à un nom différent ?</label>
			<input ng-model="devis.customer.docs.differentNameEDF" type="checkbox" id="differentNameEDF" name="differentName">
		</div>
		<!-- Bloc de champs pour le nom différent (initiallement masqué) -->
		<div ng-if="devis.customer.docs.differentNameEDF" id="differentNameEDFFields" >
			<div class="field">
				<label for="newNameEDF">Nom et prénom(s) de la facture :</label>
				<input ng-model="devis.customer.docs.newNameEDF" class="form-control" type="text" id="newNameEDF" name="newNameEDF">
			</div>
			<!-- Liste déroulante pour le lien de parenté -->
			<div class="field">
				<label for="relationship">Lien de parenté :</label>
				<select
					ng-options="relationship.key as relationship.value for relationship in relationships"
					class="form-select"
					ng-model="devis.customer.docs.relationship"
					id="relationship"
					name="relationship">
				</select>
			</div>
		</div>
		<div class="field">
			<label for="edf-number">N° EDL :</label>
			<input ng-init="devis.customer.docs.numero_edl.type='extra'" ng-model="devis.customer.docs.numero_edl.value" class="form-control" type="text" id="edf-number" name="edf-number">
		</div>
		<div class="field">
			<label for="contact-number">N° du contrat :</label>
			<input ng-init="devis.customer.docs.numero_contrat_edf.type='extra'" ng-model="devis.customer.docs.numero_contrat_edf.value" class="form-control" type="text" id="contact-number" name="contact-number">
		</div>
	</fieldset>
	<br>
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >Imposition</legend>
		<div class="field">
			<label for="ref-fiscal">Référence fiscale :</label>
			<input ng-init="devis.customer.docs.refFiscal.type='extra'" ng-model="devis.customer.docs.refFiscal.value" class="form-control" type="text" id="ref-fiscal" name="ref-fiscal">
		</div>
		<div class="field">
			<label for="select-number-person">Nombre de personne :</label>
			<select ng-init="devis.customer.docs.numberPerson.type='extra'" ng-model="devis.customer.docs.numberPerson.value" class="form-select" id="select-number-person" name="select-number-person">
				<?php
				// Boucle pour générer les options de 1 à 10
				for ($i = 1; $i <= 10; $i++) {
					echo "<option value='$i'>$i</option>";
				}
				?>
			</select>
		</div>
	</fieldset>
	<div class="button-container">
		<button class="btn btn-secondary" ng-click="previousStep()">Précédent</button>
		<button class="btn btn-info" ng-click="nextStep()">Suivant</button>
	</div>
</div>



