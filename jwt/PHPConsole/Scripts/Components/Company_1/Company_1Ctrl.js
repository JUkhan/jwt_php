import BaseCtrl from 'Scripts/Base/BaseCtrl.js';

class CompanyCtrl extends BaseCtrl
{
	constructor(scope, svc){
		super(scope);
		this.svc = svc;
		this.title='Company';
	}
}

export default CompanyCtrl;