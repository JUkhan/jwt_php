import BaseSvc from 'Scripts/Base/BaseSvc.js';

class signupSvc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	static SignupFactory(http)	{
		return new signupSvc(http);
	}
}
signupSvc.SignupFactory.$inject=['$http'];
export default signupSvc.SignupFactory;