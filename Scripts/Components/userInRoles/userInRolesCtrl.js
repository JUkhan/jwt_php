import BaseCtrl from 'Scripts/Base/BaseCtrl.js';
import JwtGrid from 'Scripts/jwt_ui/JwtGrid.js';

class userInRolesCtrl extends BaseCtrl
{
	constructor(scope, svc, timeout){
		super(scope);
		this.svc=svc;
		this.timeout=timeout;
		this.scope=scope;
	    this.loadData();
	    var gridOptions={
	        loadingText:'loading...',filter:true,
	        columns:[
	            {field:'userName', displayName:'User Name', sort:true, onClick:this.showRolesDetail.bind(this)},
	            {field:'email', displayName:'Full Name', render:row=>row.firstName+' '+row.lastName, sort:true},
	            {field:'email', displayName:'Email', sort:true},
	            {field:'email', displayName:'Roles', sort:true, render:this.getUserRoles.bind(this)},
	            {field:'email', displayName:'Action', icon:'glyphicon glyphicon-remove', linkText:'Remove', onClick:this.removeUser.bind(this)}
	            ]
	    };
	     this.jwtGrid=React.render(React.createElement(JwtGrid, {options:gridOptions}), document.getElementById('grid'));
	    this.currentUser=null;
	}
	
	removeUser(row){
	     if(confirm('Are you sure to remove the user \''+row.userName+'\'?')){
	         this.svc.removeUser(row.id).success(res=>{
	             this.arrayRemove(this.users, user=>user.id==row.id);
	              this.showMsg('User \''+row.userName+'\' removed successfully.');
	              this.JwtGrid.setData(this.users);
	         });
	     }
	}
	getUserRoles(row){
	    
	   var temp=[];
	   
	    for(let urole of this.allUserRoles){
	        if(urole.userId==row.id)
	            temp.push(urole.role);
	    }
	    return temp.join(',')||'Not Assigned';
	}
	loadData(){
	    var that=this;
	    this.syncCall(function *main(){
	        
	        let roles=yield that.svc.getAllRoles()
	        that.roles=roles.data;
	        
	        let allUserRoles=yield that.svc.call_sp('get_all_user_roles');
	        that.allUserRoles=allUserRoles.data;
	        
	        let users=yield that.svc.getAllUsers();
	        that.users=users.data;
	        
	        that.jwtGrid.setData(that.users);
	    });
	   
	    
	}

	addRole(roleName){
	   
	    if(roleName && this.roles.find(x=>x.name===roleName)){
	         this.showMsg('Already exist.');
	        return;
	    }
	    if(roleName){
	        this.svc.addRole({name:roleName}).success(res=>{
	           this.roles.push({name:roleName, id:res.data});
	           this.roleMsg='';
	           this.roleName='';
	        }).error(res=>{ this.roleMsg= res.message; });
	    }else{
	        this.showMsg('Role name is required.');
	    }
	}
	showRolesDetail(user){
	    this.currentUser=user;
	     this.scope.$apply(()=>{
        	  this.userName=user.userName;
        	  this.userRoles=this.allUserRoles.filter(x=>x.userId===user.id);
        	 
	     });
	}
    removeRole(roleId){
        if(confirm('Are you sure to remove this role?')){
            this.svc.removeRoleFromUser(this.currentUser.id, roleId).success(res=>{
                 this.arrayRemove(this.allUserRoles, x=>x.roleId==roleId && x.userId==this.currentUser.id);
                 this.showMsg('Role removed successfully');
                 this.userRoles=this.allUserRoles.filter(x=>x.userId===this.currentUser.id);
                 this.jwtGrid.setData(this.users);
            })
           
        }
    }
   
    assignRoleToUser(roleId){
        
        if(this.validateRole(roleId)){
            if(this.allUserRoles.find(x=>x.roleId==roleId)){
                 this.showMsg('already exist.');
                 return;
            }
            this.svc.assignRoleToUser(this.currentUser.id, roleId).success(res=>{
                this.roleId='';
                this.showMsg('Role assigned successfully');
                this.allUserRoles.push({userId:this.currentUser.id, roleId:roleId, role: this.roles.find(x=>x.id==roleId).name});
                this.userRoles=this.allUserRoles.filter(x=>x.userId===this.currentUser.id);
                this.jwtGrid.setData(this.users);
            })
        }
       
    }
    validateRole(claimName){
       
        if(!this.currentUser){
            this.showMsg('Please select an user first.');
            return false;
        }
        else if(!claimName){
             this.showMsg('Please select a role.');
            return false;
        }
        this.userMsg='';
        return true;
    }
    showMsg(msg) {
	    this.msg=msg;
        var that=this, timer = that.timeout(function () {
            that.timeout.cancel(timer);
           	that.msg='';
        }, 2000);
    }
}

userInRolesCtrl.$inject=['$scope', 'userInRolesSvc', '$timeout'];
export default userInRolesCtrl;