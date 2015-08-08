import BaseCtrl from 'Scripts/Base/BaseCtrl.js';
import JwtFormGrid from 'Scripts/jwt_ui/JwtFormGrid.js';

class widget1Ctrl extends BaseCtrl
{
	constructor(scope, svc){
		super(scope);
		this.svc = svc;
	    
	    this.set_formGrid_options();
	    this.load_data();
	    svc.where('customer', "name like 'ri%'").success(res=>{console.log(res);});
	}
    
    set_formGrid_options(){
	    
	     var grid={
	        filter:true,limit:15,
	        loadingText:'Loading...',
	        newItem:()=>{ this.formGrid.showForm().formRefresh(); },
	        newItemText:'Add New Customer',
	        columns:[
	          {field:'action', displayName:'Action', icon:['glyphicon glyphicon-ok','glyphicon glyphicon-remove'], linkText:['Edit','Delete'], onClick:[row=>{ this.formGrid.setFormData(row)}, this.remove.bind(this)]},
	          {field:'id', displayName:'Member Id', render:row=>{return (row.is_member?'m':'')+row.id;}},
	          {field:'name', displayName:'Name', sort:true},
	          {field:'email', displayName:'Email', sort:true},
	          {field:'phone', displayName:'Phone', sort:true},
	          {field:'mobile', displayName:'Mobile', sort:true},
	          {field:'address', displayName:'Addresss', sort:true},
	          //{field:'alt_number', displayName:'Alt number', sort:true},
	          //{field:'reference', displayName:'Reference', sort:true},
	          {field:'is_member', displayName:'Is Member',  render:row=>{ return '<input type="checkbox" '+(row.is_member==='1'?'checked':'')+' disabled/>';}}
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
	            {type:'text', name:'mobile', label:'Mobile', required:true},
	            {type:'text', name:'phone', label:'Phone'},
	            
	            {type:'text', name:'address', label:'Address', required:true},
	            {type:'text', name:'alt_number', label:'Alt Number'},
	            {type:'text', name:'reference', label:'Reference'},
	            {type:'checkbox', name:'is_member', label:'Is Member'}
	           
	            ]
	    };
	    this.formGrid=React.render(React.createElement(JwtFormGrid, {gridOptions:grid, formOptions:form}), document.getElementById('formGrid'));
	}
	
	load_data(){
	    this.svc.get_all('customer').success(res=>{
	        this.list=res.data;
	        this.formGrid.setGridData(res.data); 
	    });
	}
    remove(row, index){
	    if(confirm('Are you sure to remove this item?')){
	         this.svc.remove('customer',row.id).success(res=>{
	                this.arrayRemove(this.list, item=>item.id===row.id);
	                this.formGrid.showMessage('Removed successfully');
	         });
	    }
	}
	save(item){
	    if(!item.id){
	        this.svc.create('customer', item)
	        .success((res)=>{
	              item.id=res.data;
	              this.list.push(item);
    	          this.formGrid.showMessage('Added successfully');
    	          this.formGrid.showGrid()
	        });
	    }else{
	         this.svc.update('customer', item.id, item)
	         .success(res=>{
	              this.formGrid.showMessage('Updated successfully');
	              this.formGrid.showGrid()
	         });
	    }
	}
}
widget1Ctrl.$inject=['$scope', 'widget1Svc'];
export default widget1Ctrl;