import BaseSvc from 'Scripts/Base/BaseSvc.js';

class userInRolesSvc extends BaseSvc
{
	constructor(http, ngAuthSettings){
		super(http);
		this.apiServiceBaseUri=ngAuthSettings.apiServiceBaseUri;
	    this.http=http;
	}
	getAllRoles(){
	    
	     return  this.get_all('jwt_roles');
	}
	removeUser(id){
	    
	    return  this.call_sp('remove_user', [id]);
	}
	addRole(name){
	    
	     return  this.create('jwt_roles', name);
	}
	getAllUsers(){
	    
	    return  this.call_sp('get_all_user');
	}
	assignRoleToUser(userId, roleId){
	    
	     return  this.create('jwt_user_roles',{userId:userId, roleId:roleId});
	}
	removeRoleFromUser(userId, roleId){
	    
	     return  this.call_sp('remove_role_from_user',[userId, roleId]);
	}
	static userInRolesFactory(http, ngAuthSettings)	{
	    
		return new userInRolesSvc(http, ngAuthSettings);
	}
}
userInRolesSvc.userInRolesFactory.$inject=['$http','ngAuthSettings'];
export default userInRolesSvc.userInRolesFactory;