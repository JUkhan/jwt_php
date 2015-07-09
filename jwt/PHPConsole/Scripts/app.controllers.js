
import Company_1 from 'Scripts/Components/Company_1/Company_1Ctrl.js';
import test_3 from 'Scripts/Layouts/test_3/test_3Ctrl.js';

var moduleName='app.controllers';

angular.module(moduleName,[])
.controller('Company_1Ctrl', Company_1)
.controller('test_3Ctrl', test_3);

export default moduleName;