
import widget1 from 'Scripts/Components/widget1/widget1Ctrl.js';
import widget4 from 'Scripts/Components/widget4/widget4Ctrl.js';
import widget2_up from 'Scripts/Components/widget2_up/widget2_upCtrl.js';
import test from 'Scripts/Layouts/test/testCtrl.js';
import root from 'Scripts/Layouts/root/rootCtrl.js';

var moduleName='app.controllers';

angular.module(moduleName,[])
.controller('widget1Ctrl', widget1)
.controller('widget4Ctrl', widget4)
.controller('widget2_upCtrl', widget2_up)
.controller('testCtrl', test)
.controller('rootCtrl', root);

export default moduleName;