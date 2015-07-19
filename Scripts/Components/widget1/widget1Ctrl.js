import BaseCtrl from 'Scripts/Base/BaseCtrl.js';

class widget1Ctrl extends BaseCtrl
{
	constructor(scope, svc){
		super(scope);
		this.svc = svc;
		this.title='widget1 ...';
	}
    call_sp(){
        //sp_getCustomers   sp_addCustomer
       //this.svc.call_sp('sp_addCustomer', ['Abdulla','Abdulla@gmail.com','123','321']).
       this.svc.call_sp('sp_getCustomers').
       success(res=>{
          console.log(res); 
       });
    }
}
widget1Ctrl.$inject=['$scope', 'widget1Svc'];
export default widget1Ctrl;