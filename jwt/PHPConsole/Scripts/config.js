
export default function config(stateprovider, routeProvider){
	stateprovider.state('myRoot',{abstract:true,url:'/myRoot',templateUrl:'Scripts/Layouts/myRoot/myRoot.html',controller:'myRootCtrl as vm'});

	stateprovider.state('myRoot.Nav1',{url:'/Nav1',templateUrl:'Scripts/Components/Home/Home.html',controller:'HomeCtrl as vm'});
}
config.$inject=['$stateProvider', '$urlRouterProvider'];
