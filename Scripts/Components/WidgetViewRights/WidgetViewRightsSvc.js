import BaseSvc from 'Scripts/Base/BaseSvc.js';

class WidgetViewRightsSvc extends BaseSvc
{
	constructor(http, ngAuthSettings){
	    
		super(http);
		this.apiServiceBaseUri=ngAuthSettings.apiServiceBaseUri;
		this.http=http;
	}
	getWidgets(){
	    
	    return this.http.get('api/tools/GetAllWidgets');
	}
	getWidgetViewRights(){
	    
	     return this.get_all('widgetviewrights');
	}
	getUsers(){
	    
	     return this.get_all('jwt_user');
	}
	getRoles(){
	    
	     return this.get_all('jwt_roles');
	}
	createUVR(item){
	    
	     return this.create('widgetviewrights', item);
	}
	updateUVR(item){
	    
	     return this.update('widgetviewrights', item.id, item);
	}
	removeUVR(item){
	    
	     return this.remove('widgetviewrights', item.id);
	}
	static widgetViewRightsFactory(http, ngAuthSettings)	{
		return new WidgetViewRightsSvc(http, ngAuthSettings);
	}
}
WidgetViewRightsSvc.widgetViewRightsFactory.$inject=['$http','ngAuthSettings'];
export default WidgetViewRightsSvc.widgetViewRightsFactory;

