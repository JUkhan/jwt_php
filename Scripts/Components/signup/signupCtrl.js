
class signupCtrl 
{
	constructor(location, timeout, authService, svc){
		this.location=location;
      	this.timeout=timeout;
       	this.authService=authService;
       	
        this.svc=svc;
      	this.savedSuccessfully = false;
   		this.message = '';
        this.registration = {
          userName:'',
          password: '',
          confirmPassword: '',
          firstName:'',
          lastName:'', 
          email:'', 
          phoneNumber:''
        };
	}
	
    validate(reg){
       if(!reg.userName) {
           this.message='User name is required';
           return false;
       }
       if(!reg.firstName) {
           this.message='First name is required';
           return false;
       }
       if(!reg.lastName) {
           this.message='Last name is required';
           return false;
       }
       if(!reg.email) {
           this.message='Email is required';
           return false;
       }
       if(!this.validateEmail(reg.email)){
            this.message='Please enter valid email id';
           return;
       }
       if(!reg.phoneNumber) {
           this.message='Phonenumber is required';
           return false;
       }
       if(!reg.password) {
           this.message='Password is required';
           return false;
       }
       if(reg.password !==reg.confirmPassword) {
           this.message='Password and confirmed password are not equal';
           return false;
       }
       return true;
    }
    
    validateEmail(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    }
    
  	signUp() {
  	  var that=this;
      if(!that.validate(this.registration)){return;}
      this.svc.where('jwt_user',"userName='"+this.registration.userName+"'")
      .success(res=>{
          if(res.data.length==0){
                  that.authService.saveRegistration(that.registration).then(function (response) {
                console.log(response);
                that.savedSuccessfully = true;
                that.message = "User has been registered successfully, you will be redicted to login page in 2 seconds.";
                that.startTimer();
    
            },
             function (response) {
                 var errors = [];
                 for (var key in response.data.errors) {
                         errors.push(response.data.errors[key]);
                 }
                 that.message = "Failed to register user due to:" + errors.join(' ');
             });
          }else{
             this.message='User name is not available';  
          }
      });
        
        
    }
  
  	startTimer() {
      	let that=this;
        var timer = that.timeout(function () {
            that.timeout.cancel(timer);
           	that.location.path('root/login');
        }, 2000);
    }
     
}
signupCtrl.$inject=['$location', '$timeout', 'authService', 'signupSvc'];
export default signupCtrl;
