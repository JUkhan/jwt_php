
import Home from 'Scripts/Components/Home/HomeCtrl.js';
import myRoot from 'Scripts/Layouts/myRoot/myRootCtrl.js';

var moduleName='app.controllers';

angular.module(moduleName,[])
.controller('HomeCtrl', Home)
.controller('myRootCtrl', myRoot);

export default moduleName;