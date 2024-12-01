<div ng-if="currentStep===1" id="step1" class="step w-50">
	<!--Type d'occupant -->
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >Type d'occupant</legend>
		<div ng-init="devis.customer.type_occupant.type='extra'" class="ownership-container">
			<label for="owner">Propriétaire Occupant</label>
			<input ng-model="devis.customer.type_occupant.value" type="radio" id="owner" name="ownership" value="owner" onclick="toggleOwnerFields()" checked>
			<!-- Nouvelle option "Propriétaire Bailleur" -->
			<label for="landlord">Propriétaire Bailleur</label>
			<input ng-model="devis.customer.type_occupant.value" type="radio" id="landlord" name="ownership" value="landlord" onclick="toggleOwnerFields()">
			<label for="ownerwithouttitle">Propriétaire sans titre</label>
			<input ng-model="devis.customer.type_occupant.value" type="radio" id="ownerwithouttitle" name="ownership" value="ownerwithouttitle"
				   onclick="toggleOwnerFields()">
			<label for="tenant">Locataire</label>
			<input ng-model="devis.customer.type_occupant.value" type="radio" id="tenant" name="ownership" value="tenant" onclick="toggleOwnerFields()">
		</div>
	</fieldset>
	<div ng-if="devis.customer.type_occupant.value==='tenant'" id="owner-fields">
		<h4 id="locataire-header">Si le client est locataire, veuillez fournir :</h4>
		<label for="authorization">Une autorisation :</label>
		<file-uploader
			model="devis.customer.authorization"
			destination-path="{{devis.tmp}}/customer"
			rename-file="Authorization"
			class="form-control"
			accept="image/*,.pdf"
			multiple
			capture="environment">
		</file-uploader>
		<br>
		<label for="owner-id-recto">CNI du propriétaire (recto) :</label>
		<file-uploader
			model="devis.customer.ownerIdRecto"
			destination-path="{{devis.tmp}}/customer"
			rename-file="CNI du propriétaire (recto)"
			class="form-control"
			accept="image/*,.pdf"
			multiple
			capture="environment">
		</file-uploader>

		<br>
		<label for="owner-id-verso">CNI du propriétaire (verso) :</label>
		<file-uploader
			model="devis.customer.ownerIdVerso"
			destination-path="{{devis.tmp}}/customer"
			rename-file="CNI du propriétaire (verso)"
			class="form-control"
			accept="image/*,.pdf"
			multiple
			capture="environment">
		</file-uploader>
		<br>
		<!-- Bail de location -->
		<label for="tenantslease">Le bail de location :</label>
		<file-uploader
			model="devis.customer.tenantslease"
			destination-path="{{devis.tmp}}/customer"
			rename-file="Bail de location"
			class="form-control"
			accept="image/*,.pdf"
			multiple
			capture="environment">
		</file-uploader>
	</div>
	<br>
	<!-- Type de client -->
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >Type de client</legend>
		<div class="ownership-container">
			<label for="individual">Particulier</label>
			<input ng-model="devis.customer.clientType" type="radio" id="individual" name="clientType" value="individual">
			<label for="professional">Professionnel</label>
			<input ng-model="devis.customer.clientType" type="radio" id="professional" name="clientType" value="professional">
		</div>
	</fieldset>
	<br>
	<fieldset ng-if="devis.customer.clientType === 'individual' " class="border rounded-3 p-3" id="personal-info">
		<legend class="float-none w-auto px-3" >Informations personnelles</legend>
		<div class="field">
			<label for="fullname">Nom Prénom :</label>
			<input ng-change="searchCustomer()"  ng-model="devis.customer.name" class="form-control"  type="text" id="fullname" name="fullname">
		</div>
		<div class="field">
			<label for="phone">Téléphone :</label>
			<input ng-model="devis.customer.phone" class="form-control" type="tel" id="phone" name="phone">
		</div>
		<div class="field">
			<label for="phonemobile">Téléphone mobile :</label>
			<input ng-model="devis.customer.fax" class="form-control" type="tel" id="phonemobile" name="phonemobile">
		</div>
		<div class="field">
			<label for="email">Email :</label>
			<input ng-model="devis.customer.email" class="form-control" type="email" id="email" name="email">
		</div>
		<div class="field">
			<label for="address">Adresse :</label>
			<input ng-model="devis.customer.address" class="form-control" type="text" id="address" name="address">
		</div>
		<div class="field">
			<label for="postcode">Code postal :</label>
			<input ng-model="devis.customer.zip" class="form-control" type="text" id="postcode" name="postcode">
		</div>
		<div class="field">
			<label for="city">Commune :</label>
			<input ng-model="devis.customer.town" class="form-control" type="text" id="city" name="city">
		</div>
		<!-- Checkbox pour afficher/masquer l'adresse du chantier -->
		<div class="field">
			<label for="differentAddress">L'adresse du chantier est différente ? </label>
			<input ng-model="devis.customer.differentAddress" type="checkbox" id="differentAddress" name="differentAddress">
		</div>
		<!-- Bloc de champs pour l'adresse du chantier (initiallement masqué) -->
		<div ng-if="devis.customer.differentAddress" id="chantierAddressFields" >
			<div class="field">
				<label for="chantierAddress">L'adresse du chantier :</label>
				<input ng-model="devis.customer.chantierAddress" class="form-control" type="text" id="chantierAddress" name="chantierAddress">
			</div>
			<div class="field">
				<label for="chantierPostcode">Code postal du chantier :</label>
				<input ng-model="devis.customer.chantierPostcode" class="form-control" type="text" id="chantierPostcode" name="chantierPostcode">
			</div>
			<div class="field">
				<label for="chantierCity">Commune du chantier :</label>
				<input ng-model="devis.customer.chantierCity" class="form-control" type="text" id="chantierCity" name="chantierCity">
			</div>
		</div>
		<!-- Checkbox pour afficher/masquer le contact supplémentaire -->
		<div class="field">
			<label for="showContact">Ajouter un contact supplémentaire ?</label>
			<input ng-model="devis.customer.showContact" type="checkbox" id="showContact" name="showContact">
		</div>
		<!-- Bloc de champs pour le contact supplémentaire (initiallement masqué) -->
		<div ng-if="devis.customer.showContact" id="contactFields" >
			<div class="field">
				<label for="contactFullname">Nom Prénom du contact :</label>
				<input ng-model="devis.customer.contactFullname" class="form-control" type="text" id="contactFullname" name="contactFullname">
			</div>
			<div class="field">
				<label for="contactRelation">Fonction ou Parenté :</label>
				<input ng-model="devis.customer.contactRelation" class="form-control" type="text" id="contactRelation" name="contactRelation">
			</div>
			<div class="field">
				<label for="contactMobile">Téléphone portable du contact :</label>
				<input ng-model="devis.customer.contactMobile" class="form-control" type="tel" id="contactMobile" name="contactMobile">
			</div>
		</div>
	</fieldset>
	<fieldset ng-if="devis.customer.clientType === 'professional' " class="border rounded-3 p-3" id="professional-info" >
		<legend class="float-none w-auto px-3" >Informations professionnelles</legend>
		<div class="field">
			<label for="companyName">Nom société :</label>
			<input ng-change="searchCustomer()"  ng-model="devis.customer.name" class="form-control" type="text" id="companyName" name="companyName">
		</div>
		<div class="field">
			<label for="siret">Siret :</label>
			<input ng-model="devis.customer.idprof2" class="form-control" type="text" id="siret" name="siret">
		</div>
		<div class="field">
			<label for="industry">Secteur d'activité :</label>
			<input ng-init="devis.customer.industry.type='extra'" ng-model="devis.customer.industry.value" class="form-control" type="text" id="industry" name="industry">
		</div>
		<div class="field">
			<label for="contactName">Nom Prénom :</label>
			<input ng-init="devis.customer.contactName.type='extra'" ng-model="devis.customer.contactName.value" class="form-control" type="text" id="contactName" name="contactName">
		</div>
		<div class="field">
			<label for="position">Fonction : </label>
			<input ng-init="devis.customer.position.type='extra'" ng-model="devis.customer.position.value" class="form-control" type="text" id="position" name="position">
		</div>
		<div class="field">
			<label for="companyPhone">Téléphone :</label>
			<input ng-model="devis.customer.phone" class="form-control" type="tel" id="companyPhone" name="companyPhone">
		</div>
		<div class="field">
			<label for="companyEmail">Email :</label>
			<input ng-model="devis.customer.email" class="form-control" type="email" id="companyEmail" name="companyEmail">
		</div>
		<div class="field">
			<label for="companyAddress">Adresse :</label>
			<input ng-model="devis.customer.address" class="form-control" type="text" id="companyAddress" name="companyAddress">
		</div>
		<div class="field">
			<label for="companyPostcode">Code postal :</label>
			<input ng-model="devis.customer.zip" class="form-control" type="text" id="companyPostcode" name="companyPostcode">
		</div>
		<div class="field">
			<label for="companyCity">Commune :</label>
			<input ng-model="devis.customer.town" class="form-control" type="text" id="companyCity" name="companyCity">
		</div>
	</fieldset>
	<br>
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" > Coordonnées GPS</legend>
		<div id="map"></div>
		<div class="field">
			<label for="latitude">Latitude :</label>
			<input ng-init="devis.customer.latitude.type='extra'" ng-model="devis.customer.latitude.value" class="form-control" type="text" id="latitude" name="latitude" readonly>
		</div>
		<div class="field">
			<label for="longitude">Longitude :</label>
			<input ng-init="devis.customer.latitude.type='extra'" ng-model="devis.customer.longitude.value" class="form-control" type="text" id="longitude" name="longitude" readonly>
		</div>
		<div class="field">
			<button onclick="geolocate()">Géolocaliser</button>
		</div>
	</fieldset>
	<div class="button-container">
		<button class="btn btn-info" ng-click="nextStep()">Suivant</button>
	</div>
</div>
