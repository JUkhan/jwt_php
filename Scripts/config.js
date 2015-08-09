
export default function config(stateprovider, routeProvider){
	routeProvider.otherwise('root/nav1');

	stateprovider.state('root',{abstract:true,url:'/root',templateUrl:'api/tools/tpl/root__LAYOUT__',controller:'rootCtrl as vm'});

	stateprovider.state('root.nav1',{url:'/nav1',templateUrl:'api/tools/tpl/widget1',controller:'widget1Ctrl as vm'});
	stateprovider.state('root.nav2',{url:'/nav2',templateUrl:'api/tools/tpl/widget2',controller:'widget2Ctrl as vm'});
	stateprovider.state('root.nav3',{url:'/nav3',templateUrl:'api/tools/tpl/widget3',controller:'widget3Ctrl as vm'});
	stateprovider.state('root.report',{url:'/report',templateUrl:'api/tools/tpl/report',controller:'reportCtrl as vm'});
	stateprovider.state('signup',{url:'/signup',templateUrl:'api/tools/tpl/signup',controller:'signupCtrl as vm'});
	stateprovider.state('login',{url:'/login',templateUrl:'api/tools/tpl/login',controller:'loginCtrl as vm'});
	stateprovider.state('root.userInRoles',{url:'/userInRoles',templateUrl:'api/tools/tpl/userInRoles',controller:'userInRolesCtrl as vm'});
	stateprovider.state('root.WidgetViewNav',{url:'/WidgetViewNav',templateUrl:'api/tools/tpl/WidgetViewRights',controller:'WidgetViewRightsCtrl as vm'});
}
config.$inject=['$stateProvider', '$urlRouterProvider'];
