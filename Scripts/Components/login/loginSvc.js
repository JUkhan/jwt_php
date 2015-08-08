import BaseSvc from 'Scripts/Base/BaseSvc.js';

class loginSvc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	static LoginFactory(http)	{
		return new loginSvc(http);
	}
}
loginSvc.LoginFactory.$inject=['$http'];
export default loginSvc.LoginFactory;