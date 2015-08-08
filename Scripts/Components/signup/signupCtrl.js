
class signupCtrl 
{
	constructor(location, timeout, authService){
		this.location=location;
      	this.timeout=timeout;
       	this.authService=authService;
      
      	this.savedSuccessfully = false;
   		this.message = '';
        this.registration = {
          userName:'jasim',
          password: 'jasim',
          confirmPassword: 'jasim',
          firstName:'jasim',
          lastName:'khan', 
          email:'jasim@gmail.com', 
          phoneNumber:'01913095519'
        };
	}
  
  	signUp() {
      
      	let that=this;
        
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
    }
  
  	startTimer() {
      	let that=this;
        var timer = that.timeout(function () {
            that.timeout.cancel(timer);
           	that.location.path('root/login');
        }, 2000);
    }
     
}
signupCtrl.$inject=['$location', '$timeout', 'authService'];
export default signupCtrl;
