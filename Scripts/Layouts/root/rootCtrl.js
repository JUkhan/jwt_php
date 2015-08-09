
class rootCtrl
{
	constructor(location, authService, svc){
      
		this.authentication = authService.authentication;
      	this.authService=authService;
      	this.location=location;
      	this.hasAuthorize=true;
      	//svc.getUser(this.authentication.userName).success(user=>{this.userAction(user);});
	}
  	logOut() {
        this.authService.logOut();
        this.location.path('login');
    }
  	userAction(user){
  	    
  	    for(var claim of user.claims){
  	        if(claim.value==='Admin' || claim.value==='SuperAdmin'){
  	            this.hasAuthorize=true;
  	        }
  	    }
  	}
}
rootCtrl.$inject=['$location', 'authService'/*, 'rootSvc'*/];
export default rootCtrl;