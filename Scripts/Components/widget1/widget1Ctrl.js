import BaseCtrl from 'Scripts/Base/BaseCtrl.js';
import JwtFormGrid from 'Scripts/jwt_ui/JwtFormGrid.js';

class widget1Ctrl extends BaseCtrl
{
	constructor(scope, svc){
		super(scope);
		this.svc = svc;
	    
	    this.set_formGrid_options();
	    this.load_data();
	}
    
    set_formGrid_options(){
	    
	     var grid={
	        filter:true,limit:15,
	        loadingText:'Loading...',
	        newItem:()=>{ this.formGrid.showForm().formRefresh(); },
	        newItemText:'Add New Customer',
	        columns:[
	          {field:'action', displayName:'Action', icon:['glyphicon glyphicon-ok','glyphicon glyphicon-remove'], linkText:['Edit','Delete'],  onClick:[row=>this.formGrid.setFormData(row), this.remove.bind(this)]},
	          {field:'name', displayName:'Name', sort:true},
	          {field:'email', displayName:'Email', sort:true},
	          {field:'phone', displayName:'Phone', sort:true},
	          {field:'mobile', displayName:'Mobile', sort:true}
	          ]
	       };
	      
	       var form={
	        title:'Customer', laf:'primary',
	        formSubmit:(data, form)=>{
	           this.save(data, form); 
	          
	        },
	        formCancel:()=>{
	            this.formGrid.showGrid()
	        },
	        fields:[
	            {type:'text', name:'name', label:'Name', required:true},
	            {type:'text', name:'email', label:'Email', required:true},
	            {type:'text', name:'phone', label:'Phone', required:true},
	            {type:'text', name:'mobile', label:'Mobile', required:true}
	           
	            ]
	    };
	    this.formGrid=React.render(React.createElement(JwtFormGrid, {gridOptions:grid, formOptions:form}), document.getElementById('formGrid'));
	}
	
	load_data(){
	    this.svc.call_sp('sp_getCustomers').success(res=>{
	        this.list=res;
	        this.formGrid.setGridData(res); 
	    });
	}
    remove(row, index){
	    if(confirm('Are you sure to remove this item?')){
	         this.svc.call_sp('sp_deleteCustomer',[row.id]).success(res=>{
	                this.arrayRemove(this.list, item=>item.id===row.id);
	                this.formGrid.showMessage('Removed successfully');
	         });
	    }
	}
	save(item){
	    var params=[];
	    
	    params.push(item.name);
	    params.push(item.email);
	    params.push(item.phone);
	    params.push(item.mobile);
	   
	    if(!item.id){
	        this.svc.call_sp('sp_addCustomer', params)
	        .success((id)=>{
                  this.load_data();
    	          this.formGrid.showMessage('Added successfully');
    	          this.formGrid.showGrid()
	        });
	    }else{
	        params.push(item.id);
	         this.svc.call_sp('sp_updateCustomer', params)
	         .success(res=>{
	              this.formGrid.showMessage('Updated successfully');
	              this.formGrid.showGrid()
	         });
	    }
	}
}
widget1Ctrl.$inject=['$scope', 'widget1Svc'];
export default widget1Ctrl;