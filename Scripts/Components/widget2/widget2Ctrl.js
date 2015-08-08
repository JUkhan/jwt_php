import BaseCtrl from 'Scripts/Base/BaseCtrl.js';
import JwtFormGrid from 'Scripts/jwt_ui/JwtFormGrid.js';

class widget2Ctrl extends BaseCtrl
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
	        newItemText:'Add New Hall',
	        columns:[
	          {field:'action', displayName:'Action', icon:['glyphicon glyphicon-ok','glyphicon glyphicon-remove'], linkText:['Edit','Delete'], onClick:[row=>{ this.formGrid.setFormData(row)}, this.remove.bind(this)]},
	          
	          {field:'name', displayName:'Name', sort:true},
	          {field:'capacity', displayName:'Capacity', sort:true},
	          {field:'s1_m_rent', displayName:'s1_m_rent', sort:true},
	          {field:'s1_m_sequrity', displayName:'s1_m_security', sort:true},
	          {field:'s1_nm_rent', displayName:'s1_nm_rent', sort:true},
	          {field:'s1_nm_security', displayName:'s1_nm_security', sort:true},
	          {field:'s1_time', displayName:'s1_time'}
	          
	          ]
	       };
	    
	       var form={
	        title:'Hall', laf:'primary',
	        formSubmit:(data, form)=>{
	           this.save(data, form); 
	          
	        },
	        formCancel:()=>{
	            this.formGrid.showGrid()
	        },
	        fields:[
	            {type:'select', name:'name', label:'Hall Name', values:['Helmet','Anchor','Engle'], required:true},
	            
	            {type:'info', message:'Shift1 For Member'},
	            {type:'text', name:'s1_m_rent', label:'Rent', required:true},
	            {type:'text', name:'s1_m_sequrity', label:'Security', required:true},
	            
	            {type:'info', message:'Shift1 For Non Member'},
	            {type:'text', name:'s1_nm_rent', label:'Rent', required:true},
	            {type:'text', name:'s1_nm_security', label:'Security', required:true},
	            
	            {type:'info', message:'Shift1 Time'},
	            {type:'timepicker', name:'s1_time', label:'Time', required:true},
	            
	            {type:'info', message:'Shift2 For Member'},
	            {type:'text', name:'s2_m_rent', label:'Rent', required:true},
	            {type:'text', name:'s2_m_security', label:'Security', required:true},
	            
	            {type:'info', message:'Shift2 For Non Member'},
	            {type:'text', name:'s2_nm_rent', label:'Rent', required:true},
	            {type:'text', name:'s2_nm_security', label:'Security', required:true},
	            
	            {type:'info', message:'Shift2 Time'},
	            {type:'timepicker', name:'s2_time', label:'Time', required:true},
	            
	            {type:'text', name:'kitchen_charge', label:'Kitchen Charge', required:true},
	            {type:'text', name:'capacity', label:'Capacity', required:true}
	           
	            ]
	    };
	    this.formGrid=React.render(React.createElement(JwtFormGrid, {gridOptions:grid, formOptions:form}), document.getElementById('formGrid'));
	}
	
	load_data(){
	    this.svc.call_sp('sp_get_halls').success(res=>{
	        this.list=res.data;
	        this.formGrid.setGridData(res.data); 
	    });
	}
    remove(row, index){
	    if(confirm('Are you sure to remove this item?')){
	         this.svc.call_sp('sp_hall_remove',[row.id]).success(res=>{
	                this.arrayRemove(this.list, item=>item.id===row.id);
	                this.formGrid.showMessage('Removed successfully');
	         });
	    }
	}
	save(item){
	    var params=[];
	    params.push(item.name);
	    params.push(item.capacity);
	    params.push(item.kitchen_charge);
	    params.push(item.s1_m_rent);
	    params.push(item.s1_m_sequrity);
	    params.push(item.s1_nm_rent);
	    params.push(item.s1_nm_security);
	    params.push(item.s2_m_rent);
	    params.push(item.s2_m_security);
	    params.push(item.s2_nm_rent);
	    params.push(item.s2_nm_security);
	    params.push(item.s1_time);
	    params.push(item.s2_time);
	    if(!item.id){
	        this.svc.call_sp('sp_add_hall', params)
	        .success((id)=>{
                  this.load_data();
    	          this.formGrid.showMessage('Added successfully');
    	          this.formGrid.showGrid()
	        });
	    }else{
	        params.push(item.id);
	         this.svc.call_sp('sp_update_hall', params)
	         .success(res=>{
	              this.formGrid.showMessage('Updated successfully');
	              this.formGrid.showGrid()
	         });
	    }
	}
}
widget2Ctrl.$inject=['$scope', 'widget2Svc'];
export default widget2Ctrl;