
import widget1 from 'Scripts/Components/widget1/widget1Svc.js';
import widget2 from 'Scripts/Components/widget2/widget2Svc.js';
import widget3 from 'Scripts/Components/widget3/widget3Svc.js';
import report from 'Scripts/Components/report/reportSvc.js';
import signup from 'Scripts/Components/signup/signupSvc.js';
import login from 'Scripts/Components/login/loginSvc.js';
import userInRoles from 'Scripts/Components/userInRoles/userInRolesSvc.js';
import WidgetViewRights from 'Scripts/Components/WidgetViewRights/WidgetViewRightsSvc.js';

var moduleName='app.services';

angular.module(moduleName,[])
.factory('widget1Svc', widget1)
.factory('widget2Svc', widget2)
.factory('widget3Svc', widget3)
.factory('reportSvc', report)
.factory('signupSvc', signup)
.factory('loginSvc', login)
.factory('userInRolesSvc', userInRoles)
.factory('WidgetViewRightsSvc', WidgetViewRights);

export default moduleName;