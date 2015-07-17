import BaseSvc from 'Scripts/Base/BaseSvc.js';

class widget5Svc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	static Widget5Factory(http)	{
		return new widget5Svc(http);
	}
}
widget5Svc.Widget5Factory.$inject=['$http'];
export default widget5Svc.Widget5Factory;