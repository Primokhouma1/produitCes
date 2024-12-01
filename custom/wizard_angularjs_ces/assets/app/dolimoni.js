var dolimoni=angular.module('dolimoni', []);

dolimoni.filter('range', function() {
	return function(input, total) {
		total = parseInt(total);

		for (var i=0; i<total; i++) {
			input.push(i);
		}

		return input;
	};
});


dolimoni.directive('fileModel', ['$parse', function ($parse) {
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			var model = $parse(attrs.fileModel);
			var modelSetter = model.assign;

			element.bind('change', function() {
				scope.$apply(function() {
					modelSetter(scope, element[0].files[0]);
				});
			});
		}
	};
}]);


dolimoni.filter('yesOrNo', function() {
	return function(input) {
		return input === true || input === 'true' ? 'oui' : 'non' ;
	};
});

dolimoni.filter('NoOrYes', function() {
	return function(input) {
		return input === true || input === 'true' ? 'non' : 'oui' ;
	};
});


dolimoni.filter('ifEmpty', function() {
	return function(input, defaultValue) {
		if (angular.isUndefined(input) || input === null || input === '') {
			return defaultValue;
		}

		return input;
	}
});
