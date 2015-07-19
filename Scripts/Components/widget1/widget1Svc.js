import BaseSvc from 'Scripts/Base/BaseSvc.js';

class widget1Svc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	
	static Widget1Factory(http)	{
		return new widget1Svc(http);
	}
}
widget1Svc.Widget1Factory.$inject=['$http'];
export default widget1Svc.Widget1Factory;