
import widget1 from 'Scripts/Components/widget1/widget1Svc.js';
import widget3 from 'Scripts/Components/widget3/widget3Svc.js';

var moduleName='app.services';

angular.module(moduleName,[])
.factory('widget1Svc', widget1)
.factory('widget3Svc', widget3);

export default moduleName;