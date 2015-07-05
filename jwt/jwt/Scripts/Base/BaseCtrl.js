
class BaseCtrl{      
    constructor(scope){        
        scope.$on('FilterValueChanged', function(event, obj){this.filterValueChanged(obj);}.bind(this));
    }
    filterValueChanged(obj){
     
    } 
    initFilter() {
        var scope=this;
        if (window.sessionStorage["jwtFilter"]) {
            var ob = angular.fromJson(window.sessionStorage["jwtFilter"]);
            if (angular.isObject(ob)) {
                for (var prop in ob) {					
                    if (scope.hasOwnProperty(prop)) {
                        scope[prop] = ob[prop];
                    }
                }
            }
        }
    }
    gRunner(g){
        let it=g(),ret;
        (function iterate(val){
            ret=it.next(val);
            if(!ret.done){             
                if(ret.value){
                    if('success' in ret.value){
                        ret.value.success(iterate);
                    }
                    else if('then' in ret.value){
                        ret.value.then(iterate);
                    }
                    else{
                        iterate(ret.value);
                    }
                }
            }
        })();
    }
    arrayRemove(list, callback){
       
        var fx = function (arr) { return list.length; };
        for (var i = 0; i < fx(list) ; i++) {
            if (callback(list[i])) { list.splice(i, 1); i--; }
        }
        return list;
   
    }
    getParams(obj){
        var paramList=[];
        for(var key in obj){
            paramList.push({name:key, value:obj[key]});
        }
        return paramList;
    }
}
export default BaseCtrl;
