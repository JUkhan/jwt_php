
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
    
     printDoc(title, doc) {
        var html = '<!DOCTYPE html PUBLIC><html><head><meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />';
        html += '<style>@media print{button{display:none}} html,body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,p,blockquote{margin:0;padding:0;}img,body,html{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}ol,ul {list-style:none;}caption,th {text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;}q:before,q:after{content:\'\';}.table{ margin: 0;border-collapse: collapse;width: 100%;}.table td, .table th{border: 1px solid #000;padding:0;padding-left:2px; text-align: left;} .table th{background-color:#E9E9E9;padding-top:2px;padding-bottom:2px;}</style>';
        html += '<title>' + title + '</title></head><body style="padding:.4em;"><p></p>';
        html += '<div style="display:inline-block;width:800px;padding:.4em;"><button onclick="print();close();">Print</button><br/><div style="padding-left:170px"><img src="/Master/images/primasia.jpg" /><br/><br/></div>' + doc + '</div>';
        html += '</body></html>';
        var win = window.open('', 'printgrid', 'width = 900, height = 600, toolbar = 0,scrollbars=1,addressbar=0');
        win.document.write(html);
        
        //win.print(); win.close();
    }
}
export default BaseCtrl;
