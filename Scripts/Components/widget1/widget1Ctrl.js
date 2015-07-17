import BaseCtrl from 'Scripts/Base/BaseCtrl.js';

class widget1Ctrl extends BaseCtrl
{
	constructor(scope, svc){
		super(scope);
		this.svc = svc;
		this.title='widget1...';
	}
}

export default widget1Ctrl;