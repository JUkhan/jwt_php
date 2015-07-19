import BaseSvc from 'Scripts/Base/BaseSvc.js';

class widget2Svc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	static Widget2Factory(http)	{
		return new widget2Svc(http);
	}
}
widget2Svc.Widget2Factory.$inject=['$http'];
export default widget2Svc.Widget2Factory;