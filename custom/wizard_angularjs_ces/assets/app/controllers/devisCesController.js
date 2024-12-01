dolimoni.controller('DevisCesController',['$scope','devisCesService',function($scope,devisCesService){
    var vm=$scope;

	vm.chaufEau = {
		typeInstall : 'nouvelle',
	};

	vm.initProducts = {
		ces_creation_reseau:{
			name:'CES-CREATION-RESEAU',
			quantity:0,
			pu:0,
			ht:0,
			tva:0
		},
		ces_kit_dalle:{
			name:'CES-KIT-DALLE',
			quantity:0,
		},
		ces_kit_tuile:{
			name:'CES-KIT-TUILE',
			quantity:0,
			pu:0,
			ht:0,
			tva:0
		},
		ces_mitigeur:{
			name:'CES-MITIGEUR',
			quantity:0,
			pu:0,
			ht:0,
			tva:0
		},
		ts_200:{
			name:'TS-200',
			quantity:1,
			pu:0,
			ht:0,
			tva:0
		},
		liv_200:{
			name:'LIV-C200',
			quantity:1,
			pu:0,
			ht:0,
			tva:0
		},
		ces_d:{
			name:'CES-D',
			quantity:0,
			pu:0,
			ht:0,
			tva:0
		},
		ces_r:{
			name:'CES-R',
			quantity:0,
			pu:0,
			ht:0,
			tva:0
		},
	};

	vm.devis = {
		customer:{
			clientType:'individual',
			authorization: [],
			ownerIdRecto: [],
			ownerIdVerso: [],
			tenantslease: [],
			type_occupant:{
				value:'owner',
				type: 'extra'
			},
			docs: {
				passeport:[],
				identityFront:[],
				identityBack:[],
				bill:[],
				taxNotice:[],
				titleDeed:[],
				propertyTax:[],
				RIB:[],
				relationship : 0
			}
		},
		building : {
			typedelogement:{
				value:'individual',
				type: 'extra'
			},
			nb_piece_logement:{
				value:0,
				type: 'extra'
			},
			constructionDate:{
				value:0,
				type: 'extra'
			},
			story:{
				value:0,
				type: 'extra'
			},
			cover:{
				value:0,
				type: 'extra'
			},
			floor:{
				value:0,
				type: 'extra'
			},
			roomNumber:{
				value:0,
				type: 'extra'
			}
		},
		products: vm.initProducts,
		ces:{
			chaufEaux : [vm.chaufEau]
		}
	};
	vm.customer = {
		type:'individual',
		docs: {
			edf:{
				relationship : 0
			}
		}
	};

	vm.devis.tmp = (Math.random() + 1).toString(36).substring(2);

	vm.currentStep = 1;




	vm.relationships = [
		{'key':0, 'value':''},
		{'key':'mr', 'value':'Monsieur (M.)'},
		{'key':'mme', 'value':'Madame (Mme.)'},
		{'key':'veuf', 'value':'Veuf'},
		{'key':'enfant', 'value':'Enfant dans maison de famille'},
		{'key':'grandparents', 'value':'Grand-Parents'}
	]

	vm.constructionDates = [
		{'key':0, 'value':''},
		{'key':1, 'value':'Moins de 2 ans'},
		{'key':2, 'value':'Entre 2 et 15 ans'},
		{'key':3, 'value':'Plus de 15 ans'}
	];

	vm.stories = [
		{'key':0, 'value':''},
		{'key':1, 'value':'1 pan'},
		{'key':2, 'value':'2 pans'},
		{'key':3, 'value':'3 pans'},
		{'key':4, 'value':'4 pans'},
		{'key':5, 'value':'multi pans'}
	];

	vm.covers = [
		{'key':0, 'value':''},
		{'key':'dalle-beton', 'value':'Dalle en beton'},
		{'key':'charpente-tole', 'value':'Charpente existante + tôle'},
		{'key':'shingle', 'value':'Shingle'},
		{'key':'tole-tuile', 'value':'Tôle effet tuile'},
		{'key':'tuile', 'value':'Tuile'}
	];

	vm.floors = [
		{'key':0, 'value':''},
		{'key':'single-storey', 'value':'Plain-pied'},
		{'key':'two-storey-tole', 'value':'RDC + 1 étage'},
		{'key':'three-storey', 'value':'RDC + 2 étages'},
		{'key':'four-storey', 'value':'RDC + 3 étages'}
	];

	vm.roomNumbers = [
		{'key':0, 'value':''},
		{'key':'F1', 'value':'F1'},
		{'key':'F2', 'value':'F2'},
		{'key':'F3', 'value':'F3'},
		{'key':'F4', 'value':'F4'},
		{'key':'F5', 'value':'F5'},
		{'key':'F6', 'value':'F6'},
		{'key':'other', 'value':'Autre'},
	];

	vm.typeInstallationOptions =  [
		{'key':'nouvelle', 'value':'Nouvelle installation'},
		{'key':'remplacement', 'value':'Remplacement'},
	];

	vm.typePoseOptions =  [
		{'key':'tole', 'value':'Sur tôle'},
		{'key':'dalle', 'value':'Sur dalle'},
		{'key':'toile', 'value':'Sur tuile'},
	];
	vm.choixVolume =  [
			{'key':'200', 'value':'200 L'},
			{'key':'400', 'value':'400 L'},
			{'key':'500', 'value':'500 L'},
		];

	vm.paymentTermsOptions =  [
		{'key':'single', 'value':'A commande'},
		{'key':'multiple', 'value':'A la livraison'},
		{'key':'3530305', 'value':'35 % , 30% , 30% , 5%'},
		{'key':'50125125125125', 'value':'50 % , 12.5% , 12.5% , 12.5%'},
		{'key':'5x', 'value':'Paiement en 5X'},
		{'key':'10x', 'value':'Paiement en 10X'},
	];




	vm.finance = {
		paymentTerms: '10x'
	}

	vm.add = function () {

		var cleanObject = JSON.parse(angular.toJson(vm.devis));
		devisCesService.add(cleanObject).then(function success(data) {

			if(data.data.status=="success"){
				swal({title: "Success", text: 'Le devis a été bien créé', type: "success", showConfirmButton: false,timer: 3000});
			}else{
				swal({title: "Erreur", text: "Une erreur s'est produite : "+data.data.msg, type: "error", showConfirmButton: true});
			}

		});
	};

	vm.getRecap = function () {
		var cleanObject = JSON.parse(angular.toJson(vm.devis));
		devisCesService.getRecap(cleanObject).then(function success(data) {

			if(data.data.status=="success"){
				vm.devis['products'] = data.data.products;
				vm.devis['totalToPay'] = data.data.totalToPay;
			}else{
				console.log('ko');
			}

		});
	};

	let currentRequestPromise = null;
	vm.searchCustomer = function () {

		if(vm.devis.customer['name'].length < 3){
			return;
		}

		if (currentRequestPromise) {
			console.log('Request already in progress. Aborting this call.');
			return;
		}



		currentRequestPromise = devisCesService.searchCustomer(vm.devis.customer['name']);

		currentRequestPromise.then(function success(response) {

			if(response.data.status=="success"){
				vm.devis['customer']['id'] = response.data.customer['id'];
				vm.devis['customer']['phone'] = response.data.customer['phone'];
				vm.devis['customer']['idprof2'] = response.data.customer['idprof2'];
				vm.devis['customer']['fax'] = response.data.customer['fax'];
				vm.devis['customer']['email'] = response.data.customer['email'];
				vm.devis['customer']['address'] = response.data.customer['address'];
				vm.devis['customer']['town'] = response.data.customer['town'];
				vm.devis['customer']['zip'] = response.data.customer['zip'];
			}
		}).catch(function(error) {
			console.error('Error:', error);
		}).finally(function() {
			currentRequestPromise = null;
		});;
	};



// Fonction pour ajouter un noufveau chauffe-eau
	vm.addChauffeEau = function() {
		const newChauffeEau = {
			id: 'chauffeEau_' + Date.now(),
			typeInstall: '1',
			typePose: 'tole',
			raccordLong: false,
			mitigeur: false,
			choixVolume: '200L',
			creationReseau: false,
			primeEDF: false,
			primeDepuis: '',
		};
		vm.devis.ces.chaufEaux.push(newChauffeEau);
	};

// Fonction pour supprimer un chauffe-eau
	vm.removeChauffeEau = function(index) {
		vm.devis.ces.chaufEaux.splice(index, 1);
	};

	function resetProducts() {
		return {
			ces_creation_reseau: { name: 'CES-CREATION-RESEAU', quantity: 0, pu: 0, ht: 0, tva: 0 },
			ces_kit_dalle: { name: 'CES-KIT-DALLE', quantity: 0, pu: 0, ht: 0, tva: 0 },
			ces_kit_tuile: { name: 'CES-KIT-TUILE', quantity: 0, pu: 0, ht: 0, tva: 0 },
			ces_mitigeur: { name: 'CES-MITIGEUR', quantity: 0, pu: 0, ht: 0, tva: 0 },
			ts_200: { name: 'TS-200', quantity: 1, pu: 0, ht: 0, tva: 0 },
			liv_200: { name: 'LIV-C200', quantity: 1, pu: 0, ht: 0, tva: 0 },
			ces_d: { name: 'CES-D', quantity: 0, pu: 0, ht: 0, tva: 0 },
			ces_r: { name: 'CES-R', quantity: 0, pu: 0, ht: 0, tva: 0 },
		};
	}



	vm.nextStep = function () {
		if (validateStep(vm.currentStep)) {
			vm.currentStep++;
			updateProgressBar(vm.currentStep);
			updateBreadcrumb(vm.currentStep);

			// Sauvegarde en localStorage
			localStorage.setItem('devisStepData', JSON.stringify(vm.devis));
		} else {
			swal({ title: "Erreur", text: "Veuillez compléter tous les champs obligatoires.", type: "error", showConfirmButton: true });
		}
	};

	function updateBreadcrumb(step) {
		for (let i = 1; i <= 5; i++) {
			const breadcrumbStep = document.getElementById('breadcrumb-step' + i);
			breadcrumbStep.classList.remove('active');
		}

		const activeBreadcrumbStep = document.getElementById('breadcrumb-step' + step);
		activeBreadcrumbStep.classList.add('active');
		// Stockage de l'étape active dans le localStorage
		localStorage.setItem('activeStep', step);
	}


	vm.previousStep = function (){

		let previousStep = vm.currentStep - 1;

		const isValid = validateStep(previousStep);

		if(isValid){
			updateProgressBar(previousStep, isValid);

			updateBreadcrumb(previousStep);

			vm.currentStep--;
		}


	}

	$scope.updateTotalToPay = function() {
		let total_ht = 0;
		let total_tva = 0;
		let total_ttc = 0;

		// Parcours de tous les produits dans le devis pour calculer les totaux
		$scope.devis.produits.forEach(product => {
			const totalProductHT = product.pu * product.quantity;
			total_ht += totalProductHT;
			total_tva += totalProductHT * product.tva;
			total_ttc += totalProductHT + totalProductHT * product.tva;
		});

		// Mettre à jour l'objet totalToPay du devis
		$scope.devis.totalToPay = {
			total_ht: total_ht.toFixed(2),
			total_tva: total_tva.toFixed(2),
			total_ttc: total_ttc.toFixed(2),
			rac: 100,  // Exemple, remplacer par une valeur dynamique si nécessaire
			monthlyPayment: (total_ttc / 12).toFixed(2)  // Exemple, mensualité calculée pour 12 mois
		};
	};



	vm.range = function(min, max, step) {
		step = step || 1;
		var input = [];
		for (var i = min; i < max; i += step) {
			input.push(i);
		}
		return input;
	};

	function validateStep1() {

		let isValid = true;

		if(vm.devis.customer.clientType==='individual'){
			if(vm.devis.customer.name === '' || vm.devis.customer.name === undefined){
				var text = "Le nom est obligatoire";
				isValid = false;
				swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
			}else if(vm.devis.customer.differentAddress !== undefined && vm.devis.customer.differentAddress === true && ( vm.devis.customer.chantierAddress === '' || vm.devis.customer.chantierAddress === undefined)){
				var text = "Merci de renseigner l'adresse du chantier";
				isValid = false;
				swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
			}else if(vm.devis.customer.differentAddress !== undefined && vm.devis.customer.differentAddress === true && (vm.devis.customer.chantierPostcode === '' || vm.devis.customer.chantierPostcode === undefined)){
				var text = "Merci de renseigner le code postal du chantier";
				isValid = false;
				swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
			}else if(vm.devis.customer.differentAddress !== undefined && vm.devis.customer.differentAddress === true && (vm.devis.customer.chantierCity === '' || vm.devis.customer.chantierCity === undefined)){
				var text = "Merci de renseigner la commune du chantier";
				isValid = false;
				swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
			}else if(vm.devis.customer.showContact !== undefined && vm.devis.customer.showContact === true &&  (vm.devis.customer.contactFullname === '' || vm.devis.customer.contactFullname === undefined)){
				var text = "Merci de renseigner le nom et prénom du contact";
				isValid = false;
				swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
			}else if(vm.devis.customer.showContact !== undefined && vm.devis.customer.showContact === true && (vm.devis.customer.contactRelation === '' || vm.devis.customer.contactRelation === undefined)){
				var text = "Merci de renseigner la fonction ou parenté";
				isValid = false;
				swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
			}else if(vm.devis.customer.showContact !== undefined && vm.devis.customer.showContact === true && (vm.devis.customer.contactMobile === '' || vm.devis.customer.contactMobile === undefined)){
				var text = "Merci de renseigner le téléphone portable du contact";
				isValid = false;
				swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
			}
		}else if(true){

		}



		return isValid; // retournez true si la validation réussit, false sinon
	}

	function validateStep2() {
		let isValid = true;
		if(vm.devis.customer.docs.differentNameEDF !== undefined && vm.devis.customer.docs.differentNameEDF === true &&  (vm.devis.customer.docs.newNameEDF === '' || vm.devis.customer.docs.newNameEDF === undefined)){
			var text = "Merci de renseigner le nom et prénom(s) de la facture";
			isValid = false;
			swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
		}else if(vm.devis.customer.docs.differentNameEDF !== undefined && vm.devis.customer.docs.differentNameEDF === true &&  (vm.devis.customer.docs.relationship === 0 || vm.devis.customer.docs.relationship === undefined)){
			var text = "Merci de renseigner le lien de parenté";
			isValid = false;
			swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
		}

		return isValid;
	}

	function validateStep3() {
		let isValid = true;
		if(vm.devis.building.hasCurrentWorks !== undefined && vm.devis.building.hasCurrentWorks === true &&  (vm.devis.building.worksDescription.value === '' || vm.devis.building.worksDescription.value === undefined)){
			var text = "Merci de renseigner la description des travaux";
			isValid = false;
			swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
		}else if(vm.devis.building.hasCurrentWorks !== undefined && vm.devis.building.hasCurrentWorks === true &&  (vm.devis.building.worksProgress.value === '' || vm.devis.building.worksProgress.value === undefined)){
			var text = "Merci de renseigner l'avancement des travaux";
			isValid = false;
			swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
		}

		return isValid;
	}

	function validateStep4() {
		//Réinitialiser les produits du devis
		vm.devis.products = resetProducts();
		let isValid = true;

		console.log("test avant",vm.devis.ces.chaufEaux)
		angular.forEach(vm.devis.ces.chaufEaux, function(chaufEau,i){

			//Primé
			if (chaufEau.primeEDF && isValid){
				if(chaufEau.primeDepuis === undefined || chaufEau.primeDepuis === ''){
					var text = "Merci de renseigner la durée primé "+(+i+1);
					isValid = false;
					swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
				}
			}
		});
		console.log("test products",vm.devis.products);
		// Vérifiez si au moins un produit a une quantité > 0
		let hasProduct = Object.values(vm.devis.products).some(product => product.quantity > 0);

		if (!hasProduct) {
			isValid = false;
			swal({ title: "Attention", text: "Merci de sélectionner au moins un produit.", type: "warning", showConfirmButton: true });
		}

		return isValid;
	}


	function validateStep5() {
		let isValid = true;
		if(vm.devis.finance.showFinanceContact !== undefined && vm.devis.finance.showFinanceContact === true &&  (vm.devis.finance.financeContactFullname === '' || vm.devis.finance.financeContactFullname === undefined)){
			var text = "Merci de renseigner le nom et prénom du contact";
			isValid = false;
			swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
		}else if(vm.devis.finance.showFinanceContact !== undefined && vm.devis.finance.showFinanceContact === true &&  (vm.devis.finance.financeContactRelation === '' || vm.devis.finance.financeContactRelation === undefined)){
			var text = "Merci de renseigner la fonction ou parenté";
			isValid = false;
			swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
		}else if(vm.devis.finance.showFinanceContact !== undefined && vm.devis.finance.showFinanceContact === true &&  (vm.devis.finance.financeContactMobile === '' || vm.devis.finance.financeContactMobile === undefined)){
			var text = "Merci de renseigner le téléphone portable du contact";
			isValid = false;
			swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
		}else if(vm.devis.finance.hasDeposit !== undefined && vm.devis.finance.hasDeposit === true &&  (vm.devis.finance.depositAmount.value === '' || vm.devis.finance.depositAmount.value === undefined)){
			var text = "Merci de renseigner le montant de l'acompte";
			isValid = false;
			swal({title: "Attention", text: text, type: "warning", showConfirmButton: true});
		}else if (!vm.finance.paymentTerms || vm.finance.paymentTerms === '') {
			isValid = false;
			swal({ title: "Attention", text: "Merci de sélectionner les modalités de paiement.", type: "warning", showConfirmButton: true });
		}else{
			vm.getRecap();
		}

		return isValid;

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

	$scope.calculateCESProducts = function() {
		// Définir les produits de base à ajouter systématiquement
		const produits = [
			{ code: 'TS-200', description: 'Chauffe-eau solaire 200L', tva: 0.2, pu: 1000, quantity: 1 }, // Produit de chauffe-eau solaire 200L
			{ code: 'LIV-C200', description: 'Livraison et installation du chauffe-eau solaire', tva: 0.2, pu: 150, quantity: 1 } // Livraison et installation
		];

		// Parcours de chaque chauffe-eau ajouté dans le devis
		$scope.devis.ces.chaufEaux.forEach(chaufEau => {
			// Ajouter les produits en fonction des conditions spécifiques

			// Ajout du produit de création de réseau si le type d'installation est 'nouvelle' et le réseau est nécessaire
			if (chaufEau.typeInstall === 'nouvelle' && chaufEau.creationReseau) {
				produits.push({ code: 'CES-CREATION-RESEAU', description: 'Création de réseau', tva: 0.2, pu: 200, quantity: 1 });
			}

			// Ajouter le kit pour toit en tuile si la toiture est en tuile
			if (chaufEau.toit === 'tuile') {
				produits.push({ code: 'CES-KIT-TUILE', description: 'Kit pour toit en tuile', tva: 0.2, pu: 50, quantity: 1 });
			}

			// Ajouter le kit dalle si le kit dalle est sélectionné
			if (chaufEau.kitDalle) {
				produits.push({ code: 'CES-KIT-DALLE', description: 'Kit pour dalle', tva: 0.2, pu: 75, quantity: 1 });
			}

			// Ajouter le mitigeur si sélectionné
			if (chaufEau.mitigeur) {
				produits.push({ code: 'CES-MITIGEUR', description: 'Mitigeur', tva: 0.2, pu: 30, quantity: 1 });
			}

			// Ajouter le raccord long si sélectionné
			if (chaufEau.raccordLong) {
				produits.push({ code: 'CES-RACCORD-LONG', description: 'Raccord long', tva: 0.2, pu: 20, quantity: 1 });
			}

			// Ajouter le produit de remplacement si le type d'installation est 'remplacement'
			if (chaufEau.typeInstall === 'remplacement') {
				produits.push({ code: 'CES-R', description: 'Remplacement', tva: 0.2, pu: 150, quantity: 1 });

				// Ajouter la dépose si sélectionnée pour remplacement
				if (chaufEau.depose) {
					produits.push({ code: 'CES-D', description: 'Dépose', tva: 0.2, pu: 50, quantity: 1 });
				}
			}
		});

		// Mettre à jour le devis avec les produits calculés
		$scope.devis.produits = produits;
		$scope.updateTotalToPay();

		// Affichage des produits sélectionnés dans la console pour débogage
		console.log('Produits sélectionnés :', produits);
	};



	vm.createDevis = function (){
		console.log(vm.customer);
		console.log(vm.devis);
	}

	function updateProgressBar(step, isValid){
		const totalSteps = 6;
		const progressPercentage = Math.floor((step / totalSteps) * 100);
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


	}]);
