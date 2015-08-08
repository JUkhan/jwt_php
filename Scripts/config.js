
export default function config(stateprovider, routeProvider){
	routeProvider.otherwise('root/nav1');

	stateprovider.state('root',{abstract:true,url:'/root',templateUrl:'Scripts/Layouts/root/root.html',controller:'rootCtrl as vm'});

	stateprovider.state('root.nav1',{url:'/nav1',templateUrl:'Scripts/Components/widget1/widget1.html',controller:'widget1Ctrl as vm'});
	stateprovider.state('root.nav2',{url:'/nav2',templateUrl:'Scripts/Components/widget2/widget2.html',controller:'widget2Ctrl as vm'});
	stateprovider.state('root.nav3',{url:'/nav3',templateUrl:'Scripts/Components/widget3/widget3.html',controller:'widget3Ctrl as vm'});
	stateprovider.state('root.report',{url:'/report',templateUrl:'Scripts/Components/report/report.html',controller:'reportCtrl as vm'});
	stateprovider.state('signup',{url:'/signup',templateUrl:'Scripts/Components/signup/signup.html',controller:'signupCtrl as vm'});
	stateprovider.state('login',{url:'/login',templateUrl:'Scripts/Components/login/login.html',controller:'loginCtrl as vm'});
}
config.$inject=['$stateProvider', '$urlRouterProvider'];
