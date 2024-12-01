<div ng-if="currentStep===4" id="step4" class="step w-50">
    <fieldset class="border rounded-3 p-3">
        <legend class="float-none w-auto px-3">Infos CES 1</legend>
        <div ng-repeat="chaufEau in devis.ces.chaufEaux track by $index" class="chaufEau-container">
            <h4>Chauffe-eau {{$index + 1}}</h4>

            <!-- Type d'installation -->
            <div class="field">
                <label for="typeInstall_{{$index}}">Type d'installation :</label>

                <select  id="typeInstall_{{$index}}" ng-model="chaufEau.typeInstall"
                                            ng-options="type.key as type.value for type in typeInstallationOptions"  class="form-select">
                                    </select>
            </div>

            <!-- Champs pour Nouvelle installation -->
            <div ng-if="chaufEau.typeInstall === 'nouvelle'" id="nouvelleFields_{{$index}}">
                <div class="field">
                    <label for="creationReseau_{{$index}}">Création de réseau :</label>
                    <input type="checkbox" id="creationReseau_{{$index}}" ng-model="chaufEau.creationReseau">
                </div>
            </div>

            <!-- Champs pour Remplacement -->
            <div ng-if="chaufEau.typeInstall === 'remplacement'" id="remplacementFields_{{$index}}">
                <div class="field">
                    <label for="primeEDF_{{$index}}">Avez-vous déjà été primé par EDF ?</label>
                    <input type="checkbox" id="primeEDF_{{$index}}" ng-model="chaufEau.primeEDF" ng-change="toggleEDFFields($index)">
                </div>
                <div ng-if="chaufEau.primeEDF" id="primeDepuisFields_{{$index}}">
                    <div class="field">
                        <label>Vous êtes primé depuis :</label>
                        <input type="radio" ng-model="chaufEau.primeDepuis" value="plus10"> + de 10 ans
                        <input type="radio" ng-model="chaufEau.primeDepuis" value="moins10"> - de 10 ans
                    </div>
                </div>
            </div>

            <!-- Champs communs -->
            <div id="commonFields_{{$index}}">
                <div class="field">
                    <label for="typePose_{{$index}}">Type de pose :</label>
                    <select id="typePose_{{$index}}" ng-model="chaufEau.typePose"
                            ng-options="pose.key as pose.value for pose in typePoseOptions"  class="form-select">
                    </select>
                </div>
                <div class="field">
                    <label for="raccordLong_{{$index}}">Raccord long :</label>
                    <input type="checkbox" id="raccordLong_{{$index}}" ng-model="chaufEau.raccordLong">
                </div>
                <div class="field">
                    <label for="mitigeur_{{$index}}">Mitigeur :</label>
                    <input type="checkbox" id="mitigeur_{{$index}}" ng-model="chaufEau.mitigeur">
                </div>
                <div class="field">
                    <label for="choixVolume_{{$index}}">Choix du volume :</label>
                    <select id="choixVolume_{{$index}}" ng-model="chaufEau.choixVolume"
                            ng-options="volume as volume.value for volume in choixVolume"  class="form-select">
                    </select>
                </div>
            </div>

            <!-- Bouton de suppression -->
            <button  ng-if="$index" type="button" class="btn btn-danger me-3" ng-click="removeChauffeEau($index)">Supprimer ce Chauffe-Eau</button>
        </div>
            <div style="display: inline-block; width: 15px;"></div>
        <!-- Bouton pour ajouter un nouveau chauffe-eau solaire -->
        <div class="button-container">
        <button class="btn btn-success" type="button" ng-click="addChauffeEau()">Ajouter un Chauffe-eau</button>
       </div>
        <!-- Boutons de navigation -->
        <div class="button-container">
            <button class="btn btn-secondary" ng-click="previousStep()">Précédent</button>
            <button class="btn btn-info" ng-click="calculateCESProducts();nextStep()">Suivant</button>
        </div>
    </fieldset>
</div>
