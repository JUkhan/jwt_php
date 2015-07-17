
export default function config(stateprovider, routeProvider){
	stateprovider.state('root.test',{abstract:true,url:'/test',templateUrl:'Scripts/Layouts/test/test.html',controller:'testCtrl as vm'});
	stateprovider.state('root',{abstract:true,url:'/root',templateUrl:'Scripts/Layouts/root/root.html',controller:'rootCtrl as vm'});

	stateprovider.state('root.nav1up',{url:'/nav1up',templateUrl:'Scripts/Components/widget1/widget1.html',controller:'widget1Ctrl as vm'});
	stateprovider.state('root.test.nav2_up',{url:'/nav2_up',templateUrl:'Scripts/Components/widget2_up/widget2_up.html',controller:'widget2_upCtrl as vm'});
	stateprovider.state('root.nav4',{url:'/nav4/:name',templateUrl:'Scripts/Components/widget4/widget4.html',controller:'widget4Ctrl as vm'});
	stateprovider.state('root.test.nav5',{url:'/nav5/:hex',templateUrl:'Scripts/Components/widget5/widget5.html',controller:'widget5Ctrl as vm'});
}
config.$inject=['$stateProvider', '$urlRouterProvider'];