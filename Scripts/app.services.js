
import widget1 from 'Scripts/Components/widget1/widget1Svc.js';
import widget2_up from 'Scripts/Components/widget2_up/widget2_upSvc.js';
import widget4 from 'Scripts/Components/widget4/widget4Svc.js';
import widget5 from 'Scripts/Components/widget5/widget5Svc.js';

var moduleName='app.services';

angular.module(moduleName,[])
.factory('widget1Svc', widget1)
.factory('widget2_upSvc', widget2_up)
.factory('widget4Svc', widget4)
.factory('widget5Svc', widget5);

export default moduleName;