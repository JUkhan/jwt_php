
class rootCtrl
{
	constructor(location, authService, svc){
      
		this.authentication = authService.authentication;
      	this.authService=authService;
      	this.location=location;
      	this.hasAuthorize=false;
      	svc.call_sp('get_roles_by_userId', [this.authentication.userId]).success(res=>{this.userAction(res);});
	}
  	logOut() {
        this.authService.logOut();
        this.location.path('login');
    }
  	userAction(res){
  	    for(var info of res.data){
  	        if(info.role==='Admin' || info.role==='SuperAdmin'){
  	            this.hasAuthorize=true;
  	        }
  	    }
  	}
}
rootCtrl.$inject=['$location', 'authService', 'rootSvc'];
export default rootCtrl;