
import widget1 from 'Scripts/Components/widget1/widget1Ctrl.js';
import widget2 from 'Scripts/Components/widget2/widget2Ctrl.js';
import widget3 from 'Scripts/Components/widget3/widget3Ctrl.js';
import report from 'Scripts/Components/report/reportCtrl.js';
import signup from 'Scripts/Components/signup/signupCtrl.js';
import login from 'Scripts/Components/login/loginCtrl.js';
import userInRoles from 'Scripts/Components/userInRoles/userInRolesCtrl.js';
import WidgetViewRights from 'Scripts/Components/WidgetViewRights/WidgetViewRightsCtrl.js';
import root from 'Scripts/Layouts/root/rootCtrl.js';

var moduleName='app.controllers';

angular.module(moduleName,[])
.controller('widget1Ctrl', widget1)
.controller('widget2Ctrl', widget2)
.controller('widget3Ctrl', widget3)
.controller('reportCtrl', report)
.controller('signupCtrl', signup)
.controller('loginCtrl', login)
.controller('userInRolesCtrl', userInRoles)
.controller('WidgetViewRightsCtrl', WidgetViewRights)
.controller('rootCtrl', root);

export default moduleName;