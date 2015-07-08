
import Home from 'Scripts/Components/Home/HomeCtrl.js';
import root007 from 'Scripts/Layouts/root007/root007Ctrl.js';

var moduleName='Abdulla.controllers';

angular.module(moduleName,[])
.controller('HomeCtrl', Home)
.controller('root007Ctrl', root007);

export default moduleName;