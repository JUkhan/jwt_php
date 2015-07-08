
export default function config(stateprovider, routeProvider){
	stateprovider.state('root007',{abstract:true,url:'/root007',templateUrl:'Scripts/Layouts/root007/root007.html',controller:'root007Ctrl as vm'});

	stateprovider.state('root007.Nav1',{url:'/Nav1'0,controller:'HomeCtrl as vm'});
}
config.$inject=['$stateProvider', '$urlRouterProvider'];
