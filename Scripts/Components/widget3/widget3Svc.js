import BaseSvc from 'Scripts/Base/BaseSvc.js';

class widget3Svc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	static Widget3Factory(http)	{
		return new widget3Svc(http);
	}
}
widget3Svc.Widget3Factory.$inject=['$http'];
export default widget3Svc.Widget3Factory;