
class BaseSvc
{
    constructor(http){
        
        this.http=http;
    }
    getDummyData(obj){ 
      
           return this.http.post('Jwt/GetDummyData',obj);        		
    }
    get_1(spName, spParams){
        
        if(!angular.isArray(spParams)){
            spParams=this.getParams(spParams);
        }
         return  this.http.post('Repository/GetTableData',{spName:spName, spParams:spParams}); 
    }
    get_2(spName, spParams){
        
         if(!angular.isArray(spParams)){
            spParams=this.getParams(spParams);
         }
         return  this.http.post('Repository/getScalarValue',{spName:spName, spParams:spParams}); 
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