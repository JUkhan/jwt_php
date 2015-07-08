import BaseCtrl from 'Scripts/Base/BaseCtrl.js';

class HomeCtrl extends BaseCtrl
{
	constructor(scope, svc){
		super(scope);
		this.svc = svc;
		this.title='Home';
	}
}

export default HomeCtrl;