import BaseSvc from 'Scripts/Base/BaseSvc.js';

class HomeSvc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	static HomeFactory(http)	{
		return new HomeSvc(http);
	}
}
HomeSvc.HomeFactory.$inject=['$http'];
export default HomeSvc.HomeFactory;