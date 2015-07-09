import BaseSvc from 'Scripts/Base/BaseSvc.js';

class CompanySvc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	static CompanyFactory(http)	{
		return new CompanySvc(http);
	}
}
CompanySvc.CompanyFactory.$inject=['$http'];
export default CompanySvc.CompanyFactory;