import BaseCtrl from 'Scripts/Base/BaseCtrl.js';
import JwtForm from 'Scripts/jwt_ui/JwtForm.js';
import JwtGrid from 'Scripts/jwt_ui/JwtGrid.js';
class reportCtrl extends BaseCtrl
{
	constructor(scope, svc){
		super(scope);
		this.svc = svc;
		
		this.set_form_grid();
	}
	 
	set_form_grid(){
	    
	   var grid={
	        filter:false,limit:151,
	        loadingText:'Data not found.',
	        columns:[
	          {field:'cid', displayName:'Code'},
	          {field:'hall_name', displayName:'Hall Name', sort:true},
	          {field:'shift', displayName:'Shift', sort:true},
	          {field:'btype', displayName:'Booking Type', render:row=>{if(row.btype==='Confirmed'){return '<b style="color:red">'+row.btype+'</b>';} return row.btype;}, sort:true},
	          {field:'bdate', displayName:'Booking Date', sort:true},
	          {field:'payback_date', displayName:'Security Back', sort:true},
	          {field:'cancel_date', displayName:'Cancel Date', sort:true}
	          
	          ]
	       };
	      
	       var form={
	        title:'Report', laf:'primary', col:2,
	        buttons:[{text:'Search', className:'btn btn-primary', icon:'glyphicon glyphicon-search', onClick:this.submit.bind(this)}],
	        fields:[
	            {type:'select', name:'hall_name', label:'Select Hall', values:['All','Helmet','Anchor','Engle']},
	            {type:'select', name:'shift', label:'Select Shift', values:['All','Shift1', 'Shift2']},
	             {type:'select', name:'btype', label:'Select Booking Type', values:['All','Confirmed', 'Temporary', 'Canceled']},
	              {type:'datepicker', name:'bdate', label:'Start Date', options:{orientation:'top bottom',format:'yyyy-mm-dd'}},
	               {type:'info'},
	              {type:'datepicker', name:'edate', label:'End Date', options:{orientation:'top bottom',format:'yyyy-mm-dd'}}
	           
	            ]
	    };
	    this.form=React.render(React.createElement(JwtForm, {options:form}), document.getElementById('form'));
	    this.grid=React.render(React.createElement(JwtGrid, {options:grid}), document.getElementById('grid'));
	}
	
	submit(){
	   var obj=this.form.getFormData();
	   var where=[], isFirst=true;
	   if(obj.hall_name && obj.hall_name!=='All'){
	       if(isFirst){
	            where.push("hall_name='"+obj.hall_name+"'");
	            isFirst=false;
	       }else{
	           where.push(" and hall_name='"+obj.hall_name+"'");
	       }
	      
	   }
	   if(obj.shift && obj.shift!=='All'){
	       if(isFirst){
	            where.push("shift='"+obj.shift+"'");
	            isFirst=false;
	       }else{
	           where.push(" and shift='"+obj.shift+"'");
	       }
	       
	   }
	   if(obj.btype && obj.btype!=='All'){
	      if(obj.btype==='Canceled'){
	         if(isFirst){
    	            where.push("cancel_date > '2000-10-10'");
    	            isFirst=false;
    	       }else{
    	           where.push(" and cancel_date > '2000-10-10'");
    	       } 
	      }
	      else{
    	       if(isFirst){
    	            where.push("btype='"+obj.btype+"'");
    	            isFirst=false;
    	       }else{
    	           where.push(" and btype='"+obj.btype+"'");
    	       }
	      }
	   }
	   if(obj.bdate && obj.edate){
	       if(isFirst){
	           where.push("bdate BETWEEN '"+obj.bdate+"' and '" + obj.edate+"'");
	            isFirst=false;
	       }else{
	            where.push(" and bdate BETWEEN '"+obj.bdate+"' and '" + obj.edate+"'");
	       }
	       
	   }
	   if(isFirst){
	      where.push('id>0'); 
	   }
	  
	   this.svc.where('hall_booking',where.join(''))
	   .success(res=>{  this.grid.setData(res.data); });
	  
	}
}
reportCtrl.$inject=['$scope', 'reportSvc'];
export default reportCtrl;