import BaseSvc from 'Scripts/Base/BaseSvc.js';

class test101Svc extends BaseSvc
{
	constructor(http){
		super(http);
		this.http= http;
	}
	static Test101Factory(http)	{
		return new test101Svc(http);
	}
}
test101Svc.Test101Factory.$inject=['$http'];
export default test101Svc.Test101Factory;