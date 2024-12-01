window.onload = toggleClientType;

function toggleClientType() {
    var clientType = document.querySelector('input[name="clientType"]:checked').value;
    var personalInfo = document.getElementById('personal-info');
    var professionalInfo = document.getElementById('professional-info');

    if (clientType === 'individual') {
        personalInfo.style.display = 'block';
        professionalInfo.style.display = 'none';
    } else {
        personalInfo.style.display = 'none';
        professionalInfo.style.display = 'block';
    }
}

function toggleOwnerFields() {
    const ownerFields = document.getElementById('owner-fields');
    const tenantRadio = document.getElementById('tenant');

    if (tenantRadio.checked) {
        ownerFields.style.display = 'block';
    } else {
        ownerFields.style.display = 'none';
    }
}

function toggleClientType() {
    var clientType = document.querySelector('input[name="clientType"]:checked').value;
    var personalInfo = document.getElementById('personal-info');
    var professionalInfo = document.getElementById('professional-info');
    var residentialBuilding = document.getElementById('residential-building');
    var tertiaryBuilding = document.getElementById('tertiary-building');

    if (clientType === 'individual') {
        personalInfo.style.display = 'block';
        professionalInfo.style.display = 'none';
        residentialBuilding.checked = true; // Sélectionne le bouton radio "Résidentiel"
    } else {
        personalInfo.style.display = 'none';
        professionalInfo.style.display = 'block';
        tertiaryBuilding.checked = true; // Sélectionne le bouton radio "Tertiaire"
    }
}

// Gestion de L'OCR pour la partie EDF

// document.getElementById('identity-front').addEventListener('change', function (e) {
//     var file = e.target.files[0];
//     Tesseract.recognize(
//         file,
//         'eng+fra',
//         {logger: m => console.log(m)}
//     ).then(({data: {text}}) => {
//         console.log(text);
//         // Vous pouvez utiliser le texte ici
//     })
// })

// Gestion de la bar de progression du formulaire
let progressBar = document.getElementById("myProgressBar");

// Tesseract.recognize(myImage, 'eng+fra', {
//     logger: m => updateProgress(m.progress)
// })

function updateProgress(progress) {
    progressBar.value = progress;
}

function takePhoto(inputId) {
    const inputElement = document.getElementById(inputId);
    inputElement.click();
}

document.addEventListener('DOMContentLoaded', function () {
    const selectElement = document.getElementById('ownership');

    selectElement.addEventListener('change', (event) => {
        const header = document.getElementById('locataire-header');

        if (event.target.value === 'Locataire') {
            header.classList.add('red-text');
        } else {
            header.classList.remove('red-text');
        }
    });
});

mapboxgl.accessToken = 'pk.eyJ1IjoibGF1cmVudGx0IiwiYSI6ImNsYTJzazE3NjBnZzgzcHVydTV2MXBvd28ifQ.h_jjlVwzHjHzfX_rx55QTw';
const map = new mapboxgl.Map({
    container: 'map', style: 'mapbox://styles/mapbox/streets-v11'
});


function geolocate() {
    if (!navigator.geolocation) {
        alert("La géolocalisation n'est pas prise en charge par votre navigateur.");
        return;
    }

    navigator.geolocation.getCurrentPosition(function (position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        // Mettez à jour les champs latitude et longitude
        document.getElementById('latitude').value = latitude;
        document.getElementById('longitude').value = longitude;

        // Utilisation de l'API OpenCage Geocoder pour obtenir le code postal, la commune, le numéro et le nom de la rue
        const opencageApiKey = '0a604af2695c48c09130d7e52fae4b5f';
        const apiUrl = `https://api.opencagedata.com/geocode/v1/json?q=${latitude}+${longitude}&key=${opencageApiKey}&language=fr&pretty=1`;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.results.length > 0) {
                    const result = data.results[0];
                    const components = result.components;

                    if (components.postcode) {
                        document.getElementById('postcode').value = components.postcode;
                    }

                    if (components.city) {
                        document.getElementById('city').value = components.city;
                    }

                    let address = '';
                    if (components.house_number) {
                        address += components.house_number;
                    }

                    if (components.road) {
                        if (address) {
                            address += ' ';
                        }
                        address += components.road;
                    }

                    document.getElementById('address').value = address;
                }
            })
            .catch(error => {
                console.error(error);
            });
    }, function (error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                alert("L'utilisateur a refusé la demande de géolocalisation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Les informations de localisation sont indisponibles.");
                break;
            case error.TIMEOUT:
                alert("La demande de localisation de l'utilisateur a expiré.");
                break;
            case error.UNKNOWN_ERROR:
                alert("Une erreur inconnue s'est produite.");
                break;
        }
    });
}

function showOtherField() {
    var selectBox = document.getElementById('building-stories');
    var otherField = document.getElementById('other-field');

    if (selectBox.value === 'other') {
        otherField.style.display = 'block';
    } else {
        otherField.style.display = 'none';
    }
}

function checkOther(select) {
    var otherInput = document.getElementById('other-room-number');
    if (select.value == 'other') {
        otherInput.style.display = 'block';
    } else {
        otherInput.style.display = 'none';
    }
}

function validateStep1() {
    // Ajoutez votre logique de validation pour l'étape 1 ici
    return true; // retournez true si la validation réussit, false sinon
}

function validateStep2() {
    // Ajoutez votre logique de validation pour l'étape 2 ici
    return true;
}

function validateStep3() {
    // Ajoutez votre logique de validation pour l'étape 3 ici
    return true;
}

function validateStep4() {
    // Ajoutez votre logique de validation pour l'étape 4 ici
    return true;
}


function validateStep5() {
    // Ajoutez votre logique de validation pour l'étape 4 ici
    return true;
}

function validateStep(step) {
    switch (step) {
        case 1:
            return validateStep1();
        case 2:
            return validateStep2();
        case 3:
            return validateStep3();
        case 4:
            return validateStep4();
        case 5:
            return validateStep5();
        default:
            return false;
    }
}


function nextStep(step) {
    const isValid = validateStep(step - 1);
    if (!isValid) return;
    const currentStep = document.getElementById('step' + (step - 1));
    const nextStepElement = document.getElementById('step' + step);
    // Vérifiez si les éléments existent
    if (!currentStep || !nextStepElement) {
        console.error('Element not found:', !currentStep ? 'currentStep' : 'nextStepElement');
        return;
    }
    currentStep.style.display = 'none';
    nextStepElement.style.display = 'block';
    updateProgressBar(step, isValid);
    updateBreadcrumb(step);
}

function previousStep(step) {

    const isValid = validateStep(step);

    const currentStep = document.getElementById('step' + (step + 1));
    const previousStepElement = document.getElementById('step' + step);

    // Vérifiez si les éléments existent
    if (!currentStep || !previousStepElement) {
        console.error('Element not found:', !currentStep ? 'currentStep' : 'previousStepElement');
        return;
    }

    currentStep.style.display = 'none';
    previousStepElement.style.display = 'block';

    updateProgressBar(step, isValid);
    updateBreadcrumb(step);
}

// Stockage de la progression de la barre de progression
function updateProgressBar(step, isValid) {
    const totalSteps = 5;
    const progressPercentage = (step / totalSteps) * 100;
    const progressBar = document.getElementById('progress');
    const percentageDisplay = document.getElementById('percentage');

    progressBar.style.width = progressPercentage + '%';
    progressBar.style.backgroundColor = isValid ? 'green' : 'red';
    percentageDisplay.textContent = progressPercentage + '%';

    // Stockage de la progression dans le localStorage
    localStorage.setItem('progressPercentage', progressPercentage);

    // Stockage de l'étape active dans le localStorage
    localStorage.setItem('activeStep', step);
}

// Récupération de la progression de la barre de progression lors du chargement de la page
window.onload = function () {
    const progressPercentage = localStorage.getItem('progressPercentage');
    if (progressPercentage === null) { // Si c'est la première fois que la page est chargée
        // Nous mettons à jour la barre de progression et le fil d'ariane à l'étape "Info client"
        updateProgressBar(1, true);
        updateBreadcrumb(1);
    } else {
        // Sinon, nous récupérons l'étape sauvegardée dans le localStorage
        const progressBar = document.getElementById('progress');
        const percentageDisplay = document.getElementById('percentage');
        progressBar.style.width = progressPercentage + '%';
        percentageDisplay.textContent = progressPercentage + '%';
    }
}

// Stockage de l'étape active du fil d'ariane
function updateBreadcrumb(step) {
    for (let i = 1; i <= 4; i++) {
        const breadcrumbStep = document.getElementById('breadcrumb-step' + i);
        breadcrumbStep.classList.remove('active');
    }

    const activeBreadcrumbStep = document.getElementById('breadcrumb-step' + step);
    activeBreadcrumbStep.classList.add('active');
    // Stockage de l'étape active dans le localStorage
    localStorage.setItem('activeStep', step);
}

function resetForm() {
    // Réinitialisez le formulaire ici.
    // Vous devrez probablement sélectionner le formulaire et utiliser la méthode reset().
    // Par exemple : document.getElementById('myForm').reset();

    // Redirigez vers la première étape
    const currentStep = document.getElementById('step5'); // ou l'étape actuelle
    const firstStepElement = document.getElementById('step1');
    currentStep.style.display = 'none';
    firstStepElement.style.display = 'block';

    // Réinitialisez également toute autre information pertinente, comme la barre de progression et le fil d'ariane.
    updateProgressBar(1, true);
    updateBreadcrumb(1);
}

function submitForm() {
    // Implement form submission logic here.
    alert('Form submitted');
}

// Lorsque l'utilisateur clique sur un bouton avec l'ID "next-button", passez à l'étape suivante avec un effet de fondu
$("#next-button").on("click", function () {
    // Masquer l'étape actuelle avec un fondu
    $(".step:visible").fadeOut(400, function () {
        // Une fois le fondu terminé, afficher l'étape suivante avec un fondu
        $(".step:visible").next().fadeIn(400);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const addClimButton = document.querySelector('.add-clim-button');
    if (!addClimButton) {
        console.error("Erreur: Bouton 'Ajouter une autre clim' non trouvé");
        return;
    }

    addClimButton.addEventListener('click', addClim);
    console.log("Écouteur d'événement ajouté au bouton");
});

let climCounter = 1;

/*
function addClim() {
    climCounter++;
    const climContainer = document.createElement('div');
    climContainer.className = "clim-container";
    climContainer.innerHTML = `
        <br>
        <fieldset>
            <legend>Infos Clim ${climCounter}</legend>
            <div class="field">
                    <label for="clim-install-type">Type d'installation :</label>
                    <select id="clim-install-type" name="clim-install-type">
                        <option value="new">Nouvelle installation</option>
                        <option value="replacement">Remplacement</option>
                    </select>
                </div>
                 <div id="replacement-details" style="display:none;">
                    <div class="field">
                        <label for="old-clim-brand">Ancienne Marque :</label>
                        <select id="old-clim-brand" name="old-clim-brand">
                            <option value="Marque 0">Ciat</option>
                            <option value="Marque 1">Airwell</option>
                            <option value="Marque 2">Blue Star</option>
                            <option value="Marque 3">Carrier</option>
                            <option value="Marque 4">Daikin</option>
                            <option value="Marque 5">Fujitsu General</option>
                            <option value="Marque 6">Gree Electric Appliances</option>
                            <option value="Marque 7">Haier</option>
                            <option value="Marque 8">Hitachi</option>
                            <option value="Marque 9">Lennox</option>
                            <option value="Marque 10">LG Electronics</option>
                            <option value="Marque 11">Midea</option>
                            <option value="Marque 12">Mitsubishi Electric</option>
                            <option value="Marque 13">Panasonic</option>
                            <option value="Marque 14">Rheem</option>
                            <option value="Marque 15">Samsung Electronics</option>
                            <option value="Marque 16">Sanyo</option>
                            <option value="Marque 17">Sharp</option>
                            <option value="Marque 18">Toshiba</option>
                            <option value="Marque 19">Trane</option>
                            <option value="Marque 20">Voltas</option>
                            <option value="Marque 21">York</option>
                            <option value="Marque 22">Marque 13</option>
                            <option value="Marque 23">Marque 13</option>
                            <!-- Options similaires à celles de 'clim-brand' -->
                        </select>
                    </div>
                    <div class="field">
                        <label for="old-clim-btu">Ancienne Puissance BTU :</label>
                        <select id="old-clim-btu" name="old-clim-btu">
                            <option value="BTU 1">9000 BTU</option>
                            <option value="BTU 2">12000 BTU</option>
                            <option value="BTU 3">18000 BTU</option>
                            <option value="BTU 4">24000 BTU</option>
                            <option value="BTU 5">36000 BTU</option>
                            <!-- Options similaires à celles de 'clim-btu' -->
                        </select>
                    </div>
                    <div class="field">
                        <label for="old-clim-energy-class">Ancienne Classification énergétique :</label>
                        <select id="old-clim-energy-class" name="old-clim-energy-class">
                        <option value="AAAA">A+++</option>
                        <option value="AAA">A++</option>
                        <option value="AA">A+</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                        <option value="G">G</option>
                            <!-- Options similaires à celles de 'clim-energy-class' -->
                        </select>
                    </div>
                    <hr>
                </div>
             <div class="field">
                <label for="clim-brand">Marque :</label>
                <select id="clim-brand" name="clim-brand">
                    <option value="Marque 0">Ciat</option>
                    <option value="Marque 1">Airwell</option>
                    <option value="Marque 2">Blue Star</option>
                    <option value="Marque 3">Carrier</option>
                    <option value="Marque 4">Daikin</option>
                    <option value="Marque 5">Fujitsu General</option>
                    <option value="Marque 6">Gree Electric Appliances</option>
                    <option value="Marque 7">Haier</option>
                    <option value="Marque 8">Hitachi</option>
                    <option value="Marque 9">Lennox</option>
                    <option value="Marque 10">LG Electronics</option>
                    <option value="Marque 11">Midea</option>
                    <option value="Marque 12">Mitsubishi Electric</option>
                    <option value="Marque 13">Panasonic</option>
                    <option value="Marque 14">Rheem</option>
                    <option value="Marque 15">Samsung Electronics</option>
                    <option value="Marque 16">Sanyo</option>
                    <option value="Marque 17">Sharp</option>
                    <option value="Marque 18">Toshiba</option>
                    <option value="Marque 19">Trane</option>
                    <option value="Marque 20">Voltas</option>
                    <option value="Marque 21">York</option>
                    <option value="Marque 22">Marque 13</option>
                    <option value="Marque 23">Marque 13</option>
                    <!-- Ajoutez d'autres options au besoin -->
                </select>
            </div>
            <div class="field">
                <label for="clim-btu">Puissance BTU :</label>
                <select id="clim-btu" name="clim-btu">
                    <option value="BTU 1">9000 BTU</option>
                    <option value="BTU 2">12000 BTU</option>
                    <option value="BTU 3">18000 BTU</option>
                    <option value="BTU 4">24000 BTU</option>
                    <option value="BTU 5">36000 BTU</option>
                    <!-- Ajoutez d'autres options au besoin -->
                </select>
            </div>
            <div class="field">
                <label for="clim-energy-class">Classification énergétique :</label>
                <select id="clim-energy-class" name="clim-energy-class">
                    <option value="AAAA">A+++</option>
                    <option value="AAA">A++</option>
                    <option value="AA">A+</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>
                    <option value="G">G</option>
                    <!-- Ajoutez d'autres options au besoin -->
                </select>
            </div>
            <div class="field">
                <label for="clim-room">Pièce concernée :</label>
                <select id="clim-room" name="clim-room">
                    <option value="salon">Salon</option>
                    <option value="sejour">Séjour</option>
                    <!-- Ajoutez les autres options ici -->
                </select>
            </div>
            <div class="field">
                <label for="clim-pose">Type de Pose :</label>
                <select id="clim-pose" name="clim-pose" class="clim-pose">
                    <option value="dos-a-dos">Dos à dos</option>
                    <option value="eloignee">Éloignée</option>
                </select>
            </div>
            <div class="field">
                    <label for="clim-liaison">Longueur de liaison estimée :</label>
                    <select id="clim-liaison" name="clim-liaison" class="clim-liaison">
                        <option value="1">1M</option>
                        <option value="2">2M</option>
                        <option value="3">3M</option>
                        <option value="4">4M</option>
                        <option value="5">5M</option>
                        <option value="6">6M</option>
                    </select>
                </div>
        </fieldset>
        <button class="remove-clim-button">Retirer</button> 
    `;

    const step4 = document.getElementById('step4');
    const addClimContainer = document.getElementById('add-clim-container'); // On obtient la référence à l'élément contenant le bouton
    step4.insertBefore(climContainer, addClimContainer); // Insère le nouveau conteneur avant le conteneur du bouton
    updateClimLegends();

    // Attacher l'écouteur d'événements au sélecteur de type d'installation
    const installTypeSelector = climContainer.querySelector('#clim-install-type');
    installTypeSelector.addEventListener('change', handleInstallTypeChange);

    // Attacher l'événement de clic au bouton "Retirer"
    const removeButton = climContainer.querySelector('.remove-clim-button');
    removeButton.addEventListener('click', function () {
        step4.removeChild(climContainer);
        updateClimLegends(); // Mettre à jour les numéros des légendes après avoir retiré une clim
    });

}
*/

function updateClimLegends() {
    const climContainers = document.querySelectorAll('.clim-container');
    climContainers.forEach((container, index) => {
        const legend = container.querySelector('legend');
        legend.textContent = `Info Clim ${index + 1}`;
    });
    climCounter = climContainers.length; // Mettre à jour le compteur en fonction du nombre actuel de clims
}

function handleInstallTypeChange(event) {
    const selectElement = event.currentTarget;
    const parentFieldset = selectElement.closest('fieldset');
    const replacementDetails = parentFieldset.querySelector('#replacement-details');

    if (selectElement.value === 'replacement') {
        replacementDetails.style.display = 'block';
    } else {
        replacementDetails.style.display = 'none';
    }
}

// Lorsque le document est chargé
document.addEventListener('DOMContentLoaded', function () {
    // 2. Attacher le gestionnaire d'événements à tous les sélecteurs de type d'installation présents lors du chargement de la page
    const allInstallTypeSelectors = document.querySelectorAll('#clim-install-type');
    allInstallTypeSelectors.forEach(selector => {
        selector.addEventListener('change', handleInstallTypeChange);

        // Déclenchez manuellement l'événement change pour gérer l'état initial
        const event = new Event('change');
        selector.dispatchEvent(event);
    });
});









