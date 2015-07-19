
class BaseSvc
{
    constructor(http){
        
        this.http=http;
    }
    call_sp(spName, params){
	    
	    return this.http.post('api/sp/call',{sp_name:spName, sp_params:params||null});
	}
    exportExcel(spName, spParams, fileName){
        
         if(!angular.isArray(spParams)){
            spParams=  angular.toJson(this.getParams(spParams));
         }
       window.location='Repository/ExportExcel/?spName='+spName+'&spParams='+spParams+'&fileName='+fileName;
    }
    getParams(obj){
        var paramList=[];
        for(var key in obj){
            paramList.push({name:key, value:obj[key]});
        }
        return paramList;
    }
}
export default BaseSvc;