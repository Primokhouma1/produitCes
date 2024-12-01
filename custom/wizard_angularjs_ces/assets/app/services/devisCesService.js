dolimoni.service('devisCesService', function($http, $q) {
	this.add=function (devis){
		var request='ajax_creer_devis_ces.php';

		var data = $.param({
			devis: devis
		});

		return $http({
			method: 'POST',
			data:data,
			url: request,
			headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}

		});
	};

	this.getRecap=function (devis){
		var request='ajax_calcul_recap_devis_ces.php';

		var data = $.param({
			devis: devis
		});

		return $http({
			method: 'POST',
			data:data,
			url: request,
			headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}

		});
	};

	this.searchCustomer=function (ref){
		var request='searchCustomer.php';

		var data = {
			ref: ref
		};

		console.log('data');

		return $http({
			params: data,
			url: request,
			headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}

		});
	};

});
