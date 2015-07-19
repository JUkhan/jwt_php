
export default function config(stateprovider, routeProvider){
	routeProvider.otherwise('root/nav1');

	stateprovider.state('root',{abstract:true,url:'/root',templateUrl:'Scripts/Layouts/root/root.html',controller:'rootCtrl as vm'});

	stateprovider.state('root.nav1',{url:'/nav1',templateUrl:'Scripts/Components/widget1/widget1.html',controller:'widget1Ctrl as vm'});
	stateprovider.state('root.nav2',{url:'/nav2',templateUrl:'Scripts/Components/widget3/widget3.html',controller:'widget3Ctrl as vm'});
}
config.$inject=['$stateProvider', '$urlRouterProvider'];
