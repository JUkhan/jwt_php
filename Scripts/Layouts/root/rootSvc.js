import BaseSvc from 'Scripts/Base/BaseSvc.js';

class rootSvc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	
	static rootSvcFactory(http)	{
		return new rootSvc(http);
	}
}
rootSvc.rootSvcFactory.$inject=['$http'];
export default rootSvc.rootSvcFactory;