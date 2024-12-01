<div ng-if="currentStep===3" id="step3" class="step w-50">
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >Type de logement</legend>
		<div class="field">
			<div ng-init="devis.building.typedelogement.type='extra'" class="toggle-container">
				<input ng-model="devis.building.typedelogement.value" type="radio" id="individual-housing" name="housingType" value="individual" checked>
				<label for="individual-housing">Individuel</label>
				<input ng-model="devis.building.typedelogement.value" type="radio" id="collective-housing" name="housingType" value="collective">
				<label for="collective-housing">Collectif</label>
			</div>
		</div>
	</fieldset>
	<br>
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >Bâtiment</legend>
		<div class="field">
			<label for="construction-date">De quand date la construction ?</label>
			<select
				ng-options="constructionDate.key as constructionDate.value for constructionDate in constructionDates"
				class="form-select"
				ng-init="devis.building.constructionDate.type='extra'"
				ng-model="devis.building.constructionDate.value"
				id="construction-date"
				name="construction-date">
			</select>
		</div>
		<br>
		<div class="field">
			<label for="building-stories">Type de toiture</label>
			<select
				ng-options="story.key as story.value for story in stories"
				class="form-select"
				ng-init="devis.building.story.type='extra'"
				ng-model="devis.building.story.value"
				id="building-stories"
				name="building-stories"
				onchange="showOtherField()">
			</select>
		</div>
		<br>
		<div class="field">
			<label for="building-stories">Type de couverture</label>
			<select
				ng-options="cover.key as cover.value for cover in covers"
				class="form-select"
				ng-init="devis.building.cover.type='extra'"
				ng-model="devis.building.cover.value"
				id="building-covers"
				name="building-stories"
				onchange="showOtherField()">
			</select>
		</div>
		<br>
		<div class="field">
			<label for="building-stories">Étages :</label>
			<select
				ng-options="floor.key as floor.value for floor in floors"
				class="form-select"
				ng-init="devis.building.floor.type='extra'"
				ng-model="devis.building.floor.value"
				id="building-floors"
				name="building-floors"
				onchange="showOtherField()">
			</select>
		</div>
		<br>
		<!--             Checkbox pour afficher/masquer les informations sur l'avancée de terrasse-->
		<!--            <div class="field">-->
		<!--                <label for="terraceProgress">Il y a-t-il une avancée de terrasse ?</label>-->
		<!--                <input type="checkbox" id="terraceProgress" name="terraceProgress">-->
		<!--            </div>-->
		<!--             Bloc de champs pour l'avancée de terrasse (initialement masqué)-->
		<!--            <div id="terraceFields" style="display: none;">-->
		<!--                <div class="field">-->
		<!--                    <label for="terraceSuperficie">Superficie :</label>-->
		<!--                    <input type="number" id="terraceSuperficie" name="terraceSuperficie">-->
		<!--                </div>-->
		<!--            </div>-->
		<br>
		<!-- Checkbox pour afficher/masquer les détails sur les travaux actuels -->
		<div class="field">
			<label for="currentWorks">Le client réalise-t-il actuellement des travaux ?</label>
			<input ng-model="devis.building.hasCurrentWorks" type="checkbox" id="currentWorks" name="currentWorks">
		</div>
		<!-- Bloc de champs pour les détails sur les travaux actuels (initiallement masqué) -->
		<div ng-if="devis.building.hasCurrentWorks" id="currentWorksFields" >
			<div class="field">
				<label for="worksDescription">Description des travaux :</label>
				<textarea ng-init="devis.building.worksDescription.type='extra'" class="form-control" ng-model="devis.building.worksDescription.value" id="worksDescription" name="worksDescription"></textarea>
			</div>
			<div class="field">
				<label for="worksProgress">Avancement des travaux :</label>
				<input ng-init="devis.building.worksProgress.type='extra'" class="form-control" ng-model="devis.building.worksProgress.value" type="text" id="worksProgress" name="worksProgress">
			</div>
		</div>


	</fieldset>
	<br>
	<fieldset class="border rounded-3 p-3">
		<legend class="float-none w-auto px-3" >Nombre de pièces dans le logement</legend>
		<div class="field">
			<label for="room-number">Veuillez préciser :</label>
			<select class="form-select"
					ng-options="roomNumber.key as roomNumber.value for roomNumber in roomNumbers"
					ng-init="devis.building.roomNumber.type='extra'"
					ng-model="devis.building.nb_piece_logement.value"
					id="room-number"
					name="room-number">
			</select>
		</div>
		<div ng-if="devis.building.roomNumber === 'other' " id="other-room-number" class="field" >
			<label for="other-room-input">Préciser :</label>
			<input ng-init="devis.building.otherRoom.type='extra'" ng-model="devis.building.otherRoom.value" class="form-control" type="text" id="other-room-input" name="other-room-input">
		</div>
	</fieldset>
	<div class="button-container">
		<button class="btn btn-secondary" ng-click="previousStep()">Précédent</button>
		<button class="btn btn-info" ng-click="nextStep()">Suivant</button>
	</div>
</div>
