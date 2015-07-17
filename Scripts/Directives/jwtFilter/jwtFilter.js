

class jwtFilter{
    constructor(rootScope){       
        this.restrict='A';
        this.rootScope=rootScope;
    }
    setVal(prop, val) {
        var ob = window.sessionStorage.getItem("jwtFilter") || "{}",
        data = angular.fromJson(ob);
        data[prop] = val;
        window.sessionStorage.setItem("jwtFilter", angular.toJson(data));
    }
    link(scope, jquery, attrs, ctrl){
        var filterNaame = attrs.dbFilter || attrs.ngModel;
        scope.$watch(filterNaame, function (newVal, oldVal) {
            filterNaame=filterNaame.replace('vm.','');
            jwtFilter.instance.rootScope.$broadcast("FilterValueChanged", { name: filterNaame, newValue: newVal, oldValue: oldVal });
            jwtFilter.instance.setVal(filterNaame, newVal);
        });
    }
    static builder(rootScope){
        jwtFilter.instance=new jwtFilter(rootScope);
        return  jwtFilter.instance;
    }

}
jwtFilter.builder.$inject =['$rootScope'];
export default jwtFilter;
