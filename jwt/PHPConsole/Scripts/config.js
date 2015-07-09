
export default function config(stateprovider, routeProvider){
	stateprovider.state('test_3',{abstract:true,url:'/test_3',templateUrl:'Scripts/Layouts/test_3/test_3.html',controller:'test_3Ctrl as vm'});

	stateprovider.state('.Nav1',{url:'/Nav1',views:{'view1':{templateUrl:'Scripts/Components/Company/Company.html'}},templateUrl:'Scripts/Components/Company_1/Company_1.html',controller:'Company_1Ctrl as vm'});
}
config.$inject=['$stateProvider', '$urlRouterProvider'];
