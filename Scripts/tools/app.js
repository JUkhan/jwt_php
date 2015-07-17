angular.module("jwt2", ["ui.router", "ngResource", 'ui.bootstrap', 'LocalStorageModule'])

.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if (event.which === 13) {
                scope.$apply(function () {
                    scope.$eval(attrs.ngEnter);
                });

                event.preventDefault();
            }
        });
    };
})
.controller('ModalInstanceCtrl', ['$scope', '$modalInstance', 'data', 'jwtSvc', '$rootScope', function (scope, modalInstance, data, jwtSvc) {

    scope.sendto = data.sendto;
    scope.list = data.list;
    scope.close = function () {
        modalInstance.close();
    };
    scope.send = function (message) {
        if(!message){return;}
        jwtSvc.sendMessage(data.sender, data.sendto, message);
        scope.list.push({ sender: data.sender, message: message });
        scope.message = '';
        scrollTop();
    };
}]);





