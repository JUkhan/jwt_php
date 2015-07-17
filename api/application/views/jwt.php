
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jwt</title>

    <link href="../../Content/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../Scripts/tools/Site.css" rel="stylesheet" />

</head>
<body ng-app="app">
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index" class="navbar-brand">App Builder</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="index">Widget Relation</a></li>
                    <li><a href="JwtEx">Component Builder</a></li>
                    <li><a href="JwtComponent">Component Showcase</a></li>
                </ul>

            </div>
        </div>
    </div>
    <div class="container body-content padding-top" ng-controller="mainController">

       
                <div>


                    <input ng-show="false" type="button" value="Generate Config" class="btn btn-default" ng-click="generateConfig()" />
                   

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="alert alert-success">Layout</div>
                            <form>

                                <div class="form-group">
                                    <label>Layout Name</label><br />
                                    <input class="form-control" type="text" ng-model="lvm.LayoutName" />
                                </div>

                                <div class="form-group">
                                    <label>Parent Layout</label><br />
                                    <select class="form-control" ng-model="lvm.Extend" ng-options="item.LayoutName as item.LayoutName for item in layoutList">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <input type="button" class="btn btn-success" value="Save Layout" ng-click="addLayout(lvm)" />
                            </form>
                            <table class="table table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th> Layout Name</th>
                                        <th>Parent</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="u in layoutList">
                                        <td ng-bind="u.LayoutName"></td>
                                        <td ng-bind="u.Extend"></td>

                                        <td>
                                            <a href="javascript:;" ng-click="updateLayout(u)">Update</a>|
                                            <a href="javascript:;" ng-click="removeLayout(u)">Remove</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <div class="alert alert-success">Navigation</div>
                            <form>
                                <div class="form-group">
                                    <label>Navigation Name</label><br />
                                    <input class="form-control" type="text" ng-model="nvm.NavigationName" />
                                </div>
                                <div class="form-group">
                                    <label>Widget Name</label><br />
                                    <input class="form-control" type="text" typeahead="tpl for tpl in widgetList" ng-model="nvm.WidgetName" />
                                    <input type="button" class="btn btn-default" value="Reload Widgets" ng-click="getWidgetList()" />
                                </div>
                                <div class="form-group">
                                    <label>Parameter Name(s) </label><br />
                                    <input class="form-control" type="text" ng-model="nvm.ParamName" />
                                </div>
                                <div class="form-group">
                                    <label>Has Layout </label><br />
                                    <select class="form-control" ng-model="nvm.HasLayout" ng-options="item.LayoutName as item.LayoutName for item in layoutList">
                                        <option value=""></option>
                                    </select>
                                    <input type="button" class="btn btn-warning btn-sm" ng-click="showViewsDialog(nvm)" ng-if="!!nvm.HasLayout" value="Add Widgets" />
                                </div>

                                <input type="button" class="btn btn-success" value="Save Navigation" ng-click="addNav(nvm)" />
                            </form>
                            <table class="table table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Navigation</th>
                                        <th>Widget</th>
                                        <th>Params</th>
                                        <th>HasLayout</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="u in navList">
                                        <td ng-bind="u.NavigationName"></td>
                                        <td ng-bind="u.WidgetName"></td>
                                        <td ng-bind="u.ParamName"></td>
                                        <td ng-bind="u.HasLayout"></td>
                                        <td>
                                            <a href="javascript:;" ng-click="updateNav(u)">Update</a>|
                                            <a href="javascript:;" ng-click="removeNav(u)">Remove</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
           
            

        <hr />
        <footer>
            <p>&copy; jwt</p>
        </footer>
    </div>
   
    <script type="text/ng-template" id="myModalContent.html">
        <div class="modal-header">
            <h3 class="modal-title">Map widgets with view names</h3>
        </div>
        <div class="modal-body">
            <table class="table table-condensed table-hover table-striped">
                <thead>
                    <tr><th>View Name</th><th>Widget Name</th></tr>
                </thead>
                <tbody>
                    <tr ng-repeat="u in items">
                        <td ng-bind="u.ViewName"></td>
                        <td><input type="text" typeahead="tpl for tpl in tplList" ng-model="u.WidgetName" /></td>

                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" ng-click="ok()">OK</button>

        </div>
    </script>
    <div class="overlay">
        <div class="jwt-spinner"><b>Loading...</b></div>
    </div>
    <script src="../../Scripts/lib/jquery.min.js"></script>
    <script src="../../Scripts/lib/angular.min.js"></script>
    <script src="../../Scripts/lib/angular-resource.min.js"></script>
    <script src="../../Scripts/lib/angularui.js"></script>
    <script src="../../Scripts/lib/angular-ui-router.min.js"></script>   
    <script src="../../Scripts/tools/app2.js"></script>
    <script src="../../Scripts/tools/jwt.js"></script>
    <script src="../../Scripts/tools/mainController_jwt.js"></script>   
    <script src="../../Scripts/lib/bootstrap.min.js"></script>
</body>
</html>
