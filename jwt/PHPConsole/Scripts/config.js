
export default function config(stateprovider, routeProvider){

	stateprovider.state('Nav1',{url:'/Nav1',templateUrl:'Scripts/Components/Home/Home.html',controller:'HomeCtrl as vm'});
}
config.$inject=['$stateProvider', '$urlRouterProvider'];
