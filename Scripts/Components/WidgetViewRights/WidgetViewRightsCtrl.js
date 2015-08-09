
import BaseCtrl from 'Scripts/Base/BaseCtrl.js';
import JwtFormGrid from 'Scripts/jwt_ui/JwtFormGrid.js';

class WidgetViewRightsCtrl extends BaseCtrl
{
	constructor(scope, svc, timeout){
		super(scope);
		this.svc=svc;
		this.scope=scope;
		this.timeout=timeout;
	    this.loadData();
	    this.setFormGridOptions();
	}
	setFormGridOptions(){
	    var me=this;
	    var grid={
	        filter:true,limit:15,
	      loadingText:'Loading...',newItem:()=>{me.formGrid.showForm().formRefresh(); }, newItemText:'Add New Widget Permission',
	      columns:[
	          {field:'widgetName', displayName:'Widget Name', sort:true, render:row=>{return row.widgetName.replace('__LAYOUT__','');}},
	          {field:'roleId', displayName:'Role', sort:true},
	          {field:'userId', displayName:'User', sort:true},
	          {field:'create', displayName:'Create', render:row=>{return '<input type="checkbox" '+(row.create==1?'checked':'')+' disabled/>';}},
	          {field:'update', displayName:'Update', render:row=>{return '<input type="checkbox" '+(row.update==1?'checked':'')+' disabled/>';}},
	          {field:'delete', displayName:'Delete', render:row=>{return '<input type="checkbox" '+(row.delete==1?'checked':'')+' disabled/>';}},
	          {field:'action', displayName:'Action', icon:['glyphicon glyphicon-ok','glyphicon glyphicon-remove'], linkText:['Edit','Delete'],  onClick:[row=>this.formGrid.setFormData(row), this.remove.bind(this)]},
	          ]
	    };
	    var form={
	        title:'Widget Permission', fileUpload:true,
	        formSubmit:function(data, form){
	            me.save(data);  
	        },
	        formCancel:function(){
	            me.formGrid.showGrid()
	        },
	        validate:function(item, form){
	           
	             if(!(item.roleId || item.userId)){
        	        form.showMessage('Please select a role or user.');
        	        return false;
        	    }
        	    return true;
	        },
	        fields:[
	            {type:'select', name:'widgetName', label:'Widgets', displayField:'widgetName', valueField:'widgetId', required:true},
	            {type:'select', name:'roleId', label:'Roles',displayField:'name', valueField:'id',},
	            {type:'select', name:'userId', label:'Users', displayField:'userName', valueField:'id',},
	            {type:'checkboxInlines', name:'asd', label:'Permission', values:['create','update','delete']}
	           
	            ]
	    };
	    this.formGrid=React.render(React.createElement(JwtFormGrid, {gridOptions:grid, formOptions:form}), document.getElementById('formGrid'));
	}
	loadData(){
	    
	    this.svc.getWidgetViewRights().success(res=>{this.wvrList=res.data; this.formGrid.setGridData(res.data) });
	    this.svc.getWidgets().success(res=>{this.widgetList=res; this.formGrid.setSelectOptions('widgetName', res); });
    	this.svc.getUsers().success(res=>{ this.formGrid.setSelectOptions('userId', res.data); });
    	this.svc.getRoles().success(res=>{ this.formGrid.setSelectOptions('roleId', res.data);});
    	
	}
	remove(row, index){
	    if(confirm('Are you sure to remove this item?')){
	         this.svc.removeUVR(row).success(res=>{
	                this.arrayRemove(this.wvrList, item=>item.id==row.id);
	                this.formGrid.setGridData(this.wvrList)
	                this.formGrid.showMessage('Removed successfully');
	         });
	    }
	}
	save(item){
	    
	    if(!item.id){
	        this.svc.createUVR(item).success((id)=>{
                  item.id=id.data;
                  this.wvrList.push(item);
                  this.formGrid.setGridData(this.wvrList);
    	          this.formGrid.showMessage('Added successfully');
    	          this.formGrid.showGrid()
	        });
	    }else{
	         this.svc.updateUVR(item).success(res=>{
	              this.formGrid.setGridData(this.wvrList)
	              this.formGrid.showMessage('Updated successfully');
	              this.formGrid.showGrid()
	         });
	    }
	}
    
}
WidgetViewRightsCtrl.$inject=['$scope', 'WidgetViewRightsSvc', '$timeout'];
export default WidgetViewRightsCtrl;
