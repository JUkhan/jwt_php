import BaseCtrl from 'Scripts/Base/BaseCtrl.js';

class widget2Ctrl extends BaseCtrl
{
	constructor(scope, svc){
		super(scope);
		this.svc = svc;
		this.title='widget2';
	}
}

export default widget2Ctrl;