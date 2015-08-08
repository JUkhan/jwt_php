import BaseSvc from 'Scripts/Base/BaseSvc.js';

class reportSvc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	static ReportFactory(http)	{
		return new reportSvc(http);
	}
}
reportSvc.ReportFactory.$inject=['$http'];
export default reportSvc.ReportFactory;