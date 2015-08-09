import BaseCtrl from 'Scripts/Base/BaseCtrl.js';
import JwtFormGrid from 'Scripts/jwt_ui/JwtFormGrid.js';
class widget3Ctrl extends BaseCtrl
{
constructor(scope, svc){
		super(scope);
		this.svc = svc;
	    
	    this.set_formGrid_options();
	    this.load_data();
	    this.load_hall();
	}
    load_hall(){
	    this.svc.call_sp('sp_get_halls').success(res=>{
	        this.halls=res.data;
	    });
	}
	print(row){
	   
	    var id=row.cid.replace('r',''), data=['<table>'];
	    this.svc.find('customer', id).success(res=>{
	       if(res.data.length==0){
	           alert('Customer has been removed from.');
	           return;
	       }
	        var customer=res.data[0];
	        data.push('<tr><td>Code : </td><td>'+row.cid+'</td></tr>');
	        data.push('<tr><td>Name : </td><td>'+customer.name+'</td></tr>');
	        data.push('<tr><td>Mobile : </td><td>'+customer.mobile+'</td></tr>');
	        data.push('<tr><td>Address : </td><td>'+customer.address+'</td></tr>');
	        
	         data.push('<tr><td>Hall Name : </td><td>'+row.hall_name+'</td></tr>');
	          data.push('<tr><td>Shift : </td><td>'+row.shift+'</td></tr>');
	           data.push('<tr><td>Function Type : </td><td>'+row.ftype+'</td></tr>');
	        
	        data.push('<tr><td>Booking Date : </td><td>'+row.bdate+'</td></tr>');
	        data.push('<tr><td>Booking Type : </td><td>'+row.btype+'</td></tr>');
	         data.push('<tr><td>Confirm Date : </td><td>'+(row.confirm_date||'')+'</td></tr>');
	          data.push('<tr><td>Booking Money : </td><td>'+(row.bmoney||'')+'</td></tr>');
	        
	         data.push('</table>');
	         data.push('<br><br><br><br>');
	         data.push('<b style="border-top:solid 1px black;margin-right:5em">Booking officer</b>');
	         data.push('<b style="border-top:solid 1px black;margin-right:5em">Account officer  </b>');
	         data.push('<b style="border-top:solid 1px black;margin-right:5em">GM</b>');
	         this.printDoc('Booking Information', data.join(''));
	    })
	  
	}
	tempBooking(){
	    this.svc.call_sp('remove_temp_boking').success(res=>{
	        this.load_data();
	       alert('Removed Successfully'); 
	    });
	}
    set_formGrid_options(){
	    
	     var grid={
	        filter:true,limit:15,
	        loadingText:'Loading...',
	        buttons:[{text:'Remove Temporary Booking', className:'btn btn-default', onClick:this.tempBooking.bind(this)}],
	        newItem:()=>{ this.formGrid.showForm().formRefresh(); },
	        newItemText:'Add New Customer',
	        columns:[
	          {field:'action', displayName:'Action', icon:['glyphicon glyphicon-ok','glyphicon glyphicon-remove'], linkText:['Edit','Delete'], onClick:[row=>{ this.urow=angular.copy(row); this.formGrid.setFormData(row)}, this.remove.bind(this)]},
	          {field:'cid', displayName:'Code'},
	          {field:'hall_name', displayName:'Hall Name', sort:true},
	          {field:'shift', displayName:'Shift', sort:true},
	          {field:'btype', displayName:'Booking Type', sort:true},
	          {field:'bdate', displayName:'Booking Date', sort:true},
	          {field:'payback_date', displayName:'Security Back', sort:true},
	          {field:'cancel_date', displayName:'Cancel Date', sort:true},
	          {field:'id', displayName:'Action', linkText:['Payment','Pay Back', 'Cancel','View'], onClick:[this.payment.bind(this), this.pay_back.bind(this), this.cancel.bind(this), this.print.bind(this)]}
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
	            {type:'text', name:'cid', label:'Code', required:true},
	            {type:'select', name:'hall_name', label:'Select Hall', values:['Helmet','Anchor','Engle'], required:true},
	            {type:'select', name:'shift', label:'Select Shift', values:['Shift1', 'Shift2'], required:true},
	           
	            {type:'select', name:'ftype', label:'Select Function Type', values:['Marriage', 'Marriage Anniversary','Akdh', 'Holud', 'Wedding Ceremony','Boubhat'], required:true},
	             {type:'datepicker', name:'bdate', label:'Booking Date', options:{format:'yyyy-mm-dd'}, required:true}
	           
	            ]
	    };
	    this.formGrid=React.render(React.createElement(JwtFormGrid, {gridOptions:grid, formOptions:form}), document.getElementById('formGrid'));
	}
	
	payment(row){
	  
	  if(row.confirm_date){
	      alert("Pament already done.");
	      return;
	  }
	    var msg=[], bmoney,smoney, that=this;
	    var hall=this.halls.find(function(h){return h.name===row.hall_name;});
	   
	    if(row.cid.startsWith('r')){
	       if(row.shift==='Shift1'){
	           bmoney=hall.s1_m_rent;
	           smoney=hall.s1_m_sequrity;
	           msg.push('<div><span style="width:120px;display:inline-block">Rent</span> : <b>'+hall.s1_m_rent+'</b></div>');
	           msg.push('<div><span style="width:120px;display:inline-block">Security</span> : <b>'+hall.s1_m_sequrity+'</b></div>');
	       }else{
	            bmoney=hall.s2_m_rent;
	           smoney=hall.s2_m_security;
	           msg.push('<div><span style="width:120px;display:inline-block">Rent</span> : <b>'+hall.s2_m_rent+'</b></div>');
	           msg.push('<div><span style="width:120px;display:inline-block">Security</span> : <b>'+hall.s2_m_security+'</b></div>');
	       }
	    }
	    else{
	         if(row.shift==='Shift1'){
	             bmoney=hall.s1_nm_rent;
	           smoney=hall.s1_nm_security;
	           msg.push('<div><span style="width:120px;display:inline-block">Rent</span> : <b>'+hall.s1_nm_rent+'</b></div>');
	           msg.push('<div><span style="width:120px;display:inline-block">Security</span> : <b>'+hall.s1_nm_security+'</b></div>');
	       }else{
	           bmoney=hall.s2_nm_rent;
	           smoney=hall.s2_nm_security;
	           msg.push('<div><span style="width:120px;display:inline-block">Rent</span> : <b>'+hall.s2_nm_rent+'</b></div>');
	           msg.push('<div><span style="width:120px;display:inline-block">Security</span> : <b>'+hall.s2_nm_security+'</b></div>');
	       }
	    }
	   
	    BootstrapDialog.show({
            title: 'Payment',
            message: msg.join(''),
            buttons: [{
                label: 'Submit',
                cssClass: 'btn-primary',
                action: function(dialog) {
                   that.svc.call_sp('sp_Payment',[row.id, bmoney, smoney]).success(res=>{
	                        dialog.close(); that.load_data();
	                        that.formGrid.showMessage('Payment done successfully');
	                }); 
                }
            }, {
                label: 'Close',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
        
	}
	pay_back(row){
	    
	    if(row.sec_back){
	        alert('Pay back already done'); return;
	    }
	     var msg=[], that=this;
	     
	      msg.push('<div><span style="width:120px;display:inline-block">Security</span> : <b>'+row.smoney+'</b></div>');
	      msg.push('<div><span style="width:120px;display:inline-block">Late Fee</span> : <b><input type="text" value="'+(!row.late_fee?'':row.late_fee)+'" id="latefee"/></b></div>');
	      
	      BootstrapDialog.show({
            title: 'Security Money Back',
            message: msg.join(''),
            buttons: [{
                label: 'Submit',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var fee=$('#latefee').val()||'0';
                    var back=parseFloat(row.smoney) - parseFloat(fee);
                   that.svc.call_sp('sp_Payback',[row.id, fee , back]).success(res=>{
	                        dialog.close();that.load_data();
	                        that.formGrid.showMessage('Secured money back done successfully');
	                }); 
                }
            }, {
                label: 'Close',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
	}
	daydiff(bdate) {
	    bdate=new Date(bdate);
        return Math.round((bdate-new Date())/(1000*60*60*24));
    }
	cancel(row){
	  if(row.cancel_date!== "0000-00-00"){
	      alert('Booking already canceled');
	      return;
	  }
	    var day=this.daydiff(row.bdate);
	    var bmoney=parseFloat(row.bmoney);
	    var calMoney=0;
	    if(day>=180){
	        calMoney=(bmoney*95)/100;
	    }
	    else if(day>=90){
	        calMoney=(bmoney*90)/100;
	    }
	    else if(day>=15 && day<=30){
	        calMoney=(bmoney*60)/100;
	    }
	    else if(day>=8 && day<=14){
	        calMoney=(bmoney*25)/100;
	    }
	    else if(day>=1 && day<=7){
	        calMoney=(bmoney*10)/100;
	    }
	   
	     var msg=[], that=this;
	     msg.push('<div><span style="width:120px;display:inline-block">Booking Money</span> : <b>'+row.bmoney+'</b></div>');
	     msg.push('<div><span style="width:120px;display:inline-block">Refund Money</span> : <b>'+calMoney+'</b></div>');
	     BootstrapDialog.show({
            title: 'Booking Cancellation',
            message: msg.join(''),
            buttons: [{
                label: 'Submit',
                cssClass: 'btn-primary',
                action: function(dialog) {
                      if(!confirm('Are you sure to cancel this booking ?')){return;}
                   that.svc.call_sp('sp_cance',[row.id, calMoney]).success(res=>{
	                        dialog.close();that.load_data();
	                        that.formGrid.showMessage('Booking canceled successfully');
	                }); 
                }
            }, {
                label: 'Close',
                action: function(dialog) {
                    dialog.close();
                }
            }]});
	}
	

	load_data(){
	    this.svc.call_sp('sp_get_allbooking').success(res=>{
	        this.list=res.data;
	        this.formGrid.setGridData(res.data); 
	    });
	}
    remove(row, index){
	    if(confirm('Are you sure to remove this item?')){
	         this.svc.call_sp('sp_remove_booking',[row.id]).success(res=>{
	                this.arrayRemove(this.list, item=>item.id===row.id);
	                this.formGrid.showMessage('Removed successfully');
	         });
	    }
	}
	save(item){
	    var params=[];
	    
	    params.push(item.cid);
	    params.push(item.hall_name);
	    params.push(item.shift);
	    params.push(item.ftype);
	    params.push(item.bdate);
	    var t=item.id?this.urow:item;
	    
	    if(this.list.find(function(x){return x.hall_name===t.hall_name && x.shift===t.shift && x.bdate==t.bdate;})){
	        this.formGrid.showMessage('Not Available');
	        return;
	    }
	    var id=item.cid.replace('r','');
	    this.svc.find('customer', id).success(res=>{
	        if(res.data.length==0){
	            alert('Invalid customer code');
	            return;
	        }
    	    if(!item.id){
    	        this.svc.call_sp('sp_add_booking', params)
    	        .success((id)=>{
                      this.load_data();
        	          this.formGrid.showMessage('Added successfully');
        	          this.formGrid.showGrid()
    	        });
    	    }else{
    	        params.shift(item.cid);
    	        params.push(item.id);
    	         this.svc.call_sp('sp_update_booking', params)
    	         .success(res=>{
    	              this.formGrid.showMessage('Updated successfully');
    	              this.formGrid.showGrid()
    	         });
    	    }
	    });
	}
}
widget3Ctrl.$inject=['$scope', 'widget3Svc'];
export default widget3Ctrl;