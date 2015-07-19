
import widget1 from 'Scripts/Components/widget1/widget1Ctrl.js';
import widget3 from 'Scripts/Components/widget3/widget3Ctrl.js';
import root from 'Scripts/Layouts/root/rootCtrl.js';

var moduleName='app.controllers';

angular.module(moduleName,[])
.controller('widget1Ctrl', widget1)
.controller('widget3Ctrl', widget3)
.controller('rootCtrl', root);

export default moduleName;