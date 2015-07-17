import BaseSvc from 'Scripts/Base/BaseSvc.js';

class widget4Svc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	static Widget4Factory(http)	{
		return new widget4Svc(http);
	}
}
widget4Svc.Widget4Factory.$inject=['$http'];
export default widget4Svc.Widget4Factory;