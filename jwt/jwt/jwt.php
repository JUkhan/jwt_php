<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jwt</title>

    <link href="Content/css/bootstrap.min.css" rel="stylesheet" />
    <link href="Scripts/tools/Site.css" rel="stylesheet" />

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
                <a href="Jwt" class="navbar-brand">App Builder</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="Jwt">Widget Relation</a></li>
                    <li><a href="JwtEx">Component Builder</a></li>
                    <li><a href="JwtComponent">Component Showcase</a></li>
                </ul>

            </div>
        </div>
    </div>
    <div class="container body-content padding-top" ng-controller="mainController">

        <tabset>
            <tab heading="Navigation">
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
                                    <input type="button" class="btn btn-default" value="Reload Widgets" ng-click="getTemplateList()" />
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
            </tab>
            <tab heading="Widget">
                <div class="padding-top">
                    <input class="btn btn-default" value="Load Entities" type="button" ng-click="GetEntityList()" />
                    <input class="btn btn-success" value="Create {{selectedEntity}} Widget" type="button" ng-click="CodeGenerate()" />
                    <br /><br />
                    <div class="row">
                        <div class="col-md-3">
                            <ol>
                                <li ng-repeat="u in entities">
                                    <a href="javascript:;" ng-click="entityDetails(u)">{{u}}</a>
                                </li>
                            </ol>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-7">
                                    <b>{{selectedEntity}}</b>
                                    <ol>
                                        <li ng-repeat="u in propList">
                                            <label ng-if="!u.HasDetail"><input ng-checked="u.Checked" ng-model="u.Checked" type="checkbox"><span> {{u.PropertyName}}</span></label>
                                            <a href="javascript:;" ng-click="showDetails(u)" ng-if="u.HasDetail">{{u.PropertyName}}</a>
                                            <div ng-if="u.Checked" class="well well-ls">

                                                <div> <label><input ng-checked="u.IsReq" ng-model="u.IsReq" type="checkbox"><span>Required</span></label> </div>
                                                <div ng-if="u.IsReq">
                                                    <div>Required validation message</div>
                                                    <input ng-model="u.ReqMsg" class="msg" type="text" />
                                                </div>


                                                <div> <label><input ng-checked="u.IsMin" ng-model="u.IsMin" type="checkbox"><span>MinLength</span></label></div>
                                                <div ng-if="u.IsMin">
                                                    <div>Min Width</div>
                                                    <input ng-model="u.MinLength" type="text" />
                                                    <div>Min validation message</div>
                                                    <input ng-model="u.MinMsg" class="msg" type="text" />
                                                </div>
                                                <div><label><input ng-checked="u.IsMax" ng-model="u.IsMax" type="checkbox"><span>MaxLength</span></label><br /></div>
                                                <div ng-if="u.IsMax">
                                                    <div>Max Width</div>
                                                    <input ng-model="u.MaxLength" type="text" />
                                                    <div>Max validation message</div>
                                                    <input ng-model="u.MaxMsg" class="msg" type="text" />
                                                </div>
                                                <label>UI Type</label>
                                                <select ng-model="u.UiType">
                                                    <option value="text">Text</option>
                                                    <option value="number">Number</option>
                                                    <option value="email">Email</option>
                                                    <option value="date">Date</option>
                                                    <option value="password">Password</option>
                                                    <option value="textarea">Text Area</option>
                                                    <option value="tel">Phone</option>
                                                    <option value="select">select</option>

                                                </select>



                                            </div>
                                        </li>
                                    </ol>
                                </div>
                                <div class="col-md-5">
                                    <b>{{selectedEntityDetails}}</b>
                                    <ol>
                                        <li ng-repeat="d in details">
                                            <label><input ng-checked="d.Checked" ng-model="d.Checked" type="checkbox"><span>{{d.PropertyName}}</<span></label>

                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </tab>

        </tabset>

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
    <script src="Scripts/lib/jquery.min.js"></script>
    <script src="Scripts/lib/angular.min.js"></script>
    <script src="Scripts/lib/angular-resource.min.js"></script>
    <script src="Scripts/lib/angularui.js"></script>
    <script src="Scripts/lib/angular-ui-router.min.js"></script>   
    <script src="Scripts/tools/app2.js"></script>
    <script src="Scripts/tools/jwt.js"></script>
    <script src="Scripts/tools/mainController_jwt.js"></script>   
    <script src="Scripts/lib/bootstrap.min.js"></script>
</body>
</html>
