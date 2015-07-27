
class BaseSvc
{
    constructor(http){
        
        this.http=http;
    }
    call_sp(spName, params){
	    
	    return this.http.post('api/sp/call',{sp_name:spName, sp_params:params||null});
	}
	where(tableName, where, fieldName='id', order='DESC' ){
	    return this.http.post('api/sp/where',{tableName:tableName, where:where, fieldName:fieldName, order:order});
	}
	get_all(tableName, fieldName='id', order='DESC' ){
	    return this.http.post('api/sp/get_all',{tableName:tableName, fieldName:fieldName, order:order});
	}
	find(tableName, id ,primaryKeyName='id'){
	    return this.http.post('api/sp/find',{tableName:tableName, id:id, pk:primaryKeyName});
	}
	remove(tableName, id ,primaryKeyName='id'){
	    return this.http.post('api/sp/remove',{tableName:tableName, id:id, pk:primaryKeyName});
	}
	create(tableName, obj ){
	    obj.tableName=tableName;
	    return this.http.post('api/sp/create',obj);
	}
	update(tableName, id, obj, primaryKeyName='id'){
	    obj.tableName=tableName;
	    obj.id=id;
	    obj.pk=primaryKeyName;
	    return this.http.post('api/sp/update',obj);
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