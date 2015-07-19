import BaseCtrl from 'Scripts/Base/BaseCtrl.js';

class widget3Ctrl extends BaseCtrl
{
	constructor(scope, svc){
		super(scope);
		this.svc = svc;
		this.title='widget3';
	}
}
widget3Ctrl.$inject=['$scope', 'widget3Svc'];
export default widget3Ctrl;