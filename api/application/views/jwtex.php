<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jwtEx</title>
    <link href="../../Content/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../Scripts/tools/Site.css" rel="stylesheet" />   
    <link href="../../Scripts/toastr/toastr.css" rel="stylesheet" />
   
</head>
<body ng-app="jwt2">
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
                    <li><a href="index">Widget Relation</a></li>
                    <li class="active"><a href="JwtEx">Component Builder</a></li>
                    <li><a href="JwtComponent">Component Showcase</a></li>
                </ul>

            </div>
        </div>
    </div>
    <div class="xcontainer body-content" ng-controller="mainController">

        <div class="panel panel-default margin-top">
            <div class="panel-heading">
                <div class="btn-group">
                    <label ng-click="changeDataMode('Base')" class="btn btn-success" ng-model="dataMode" btn-radio="'Base'">Base</label>
                    <label ng-click="changeDataMode('Layouts')" class="btn btn-success" ng-model="dataMode" btn-radio="'Layouts'">Layouts</label>
                    <label ng-click="changeDataMode('Widgets')" class="btn btn-success" ng-model="dataMode" btn-radio="'Widgets'">Widgets</label>
                    <label ng-click="changeDataMode('Components')" class="btn btn-success" ng-model="dataMode" btn-radio="'Components'">Components</label>
                    <label ng-click="changeDataMode('Modules')" class="btn btn-success" ng-model="dataMode" btn-radio="'Modules'">Modules</label>
                </div>

                <select class="form-control" ng-show="items.length>0" ng-change="changeItemValue()" ng-model="itemValue">
                    <option ng-repeat="u in items" value="{{$index}}" ng-bind="u"></option>
                </select>
                <input class="form-control" style="width:150px" type="text" ng-model="itemName" placeholder="w/c/m" />
                <div class="btn-group">
                    <input type="button" class="btn btn-success" value="Add Widget" ng-click="addItem(itemName,'Widgets')" />
                    <input type="button" class="btn btn-success" value="Add Component" ng-click="addItem(itemName,'Components')" />
                    <input type="button" class="btn btn-success" value="Add Module" ng-click="addItem(itemName,'Modules')" />
                </div>
                <div class="chat">
                    <input class="btn btn-default" ng-click="onclick()" type="button" value="Developers" />
                    <div class="content" ng-show="toggle">
                        <div class="user" ng-repeat="u in users" ng-click="showPopup(u)">{{u}}</div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <tabset>
                    <tab heading="Javascript">
                        <div class="panel panel-default margin-top mx">
                            <div class="panel-heading">

                                <input type="text" ng-model="jsFileName"  class="form-control" />
                                <div class="btn-group">
                                    <input class="btn btn-success" type="button" ng-click="jsLoad(jsFileName)" value="Load" />
                                    <input class="btn btn-success" type="button" value="Save" ng-click="saveJsFile()" />
                                    <input class="btn btn-success" type="button" value="Add File" ng-click="addFile(jsFileName,'.js')" />
                                    <input class="btn btn-success" type="button" value="Remove File" ng-click="removeFile(jsFileName, '.js')" />
                                </div>
                                <select class="form-control" size="1" ng-model="theme" ng-change="setTheme(theme)">

                                    <optgroup label="Bright"><option value="ace/theme/chrome">Chrome</option><option value="ace/theme/clouds">Clouds</option><option value="ace/theme/crimson_editor">Crimson Editor</option><option value="ace/theme/dawn">Dawn</option><option value="ace/theme/dreamweaver">Dreamweaver</option><option value="ace/theme/eclipse">Eclipse</option><option value="ace/theme/github">GitHub</option><option value="ace/theme/solarized_light">Solarized Light</option><option value="ace/theme/textmate">TextMate</option><option value="ace/theme/tomorrow">Tomorrow</option><option value="ace/theme/xcode">XCode</option><option value="ace/theme/kuroir">Kuroir</option><option value="ace/theme/katzenmilch">KatzenMilch</option></optgroup>
                                    <optgroup label="Dark"><option value="ace/theme/ambiance">Ambiance</option><option value="ace/theme/chaos">Chaos</option><option value="ace/theme/clouds_midnight">Clouds Midnight</option><option value="ace/theme/cobalt">Cobalt</option><option value="ace/theme/idle_fingers">idle Fingers</option><option value="ace/theme/kr_theme">krTheme</option><option value="ace/theme/merbivore">Merbivore</option><option value="ace/theme/merbivore_soft">Merbivore Soft</option><option value="ace/theme/mono_industrial">Mono Industrial</option><option value="ace/theme/monokai">Monokai</option><option value="ace/theme/pastel_on_dark">Pastel on dark</option><option value="ace/theme/solarized_dark">Solarized Dark</option><option value="ace/theme/terminal">Terminal</option><option value="ace/theme/tomorrow_night">Tomorrow Night</option><option value="ace/theme/tomorrow_night_blue">Tomorrow Night Blue</option><option value="ace/theme/tomorrow_night_bright">Tomorrow Night Bright</option><option value="ace/theme/tomorrow_night_eighties">Tomorrow Night 80s</option><option value="ace/theme/twilight">Twilight</option><option value="ace/theme/vibrant_ink">Vibrant Ink</option></optgroup>
                                </select>
                                <select class="form-control" ng-model="fontSize" ng-change="setFont(fontSize)">
                                   
                                    <option value="10px">10px</option>
                                    <option value="11px">11px</option>
                                    <option value="12px">12px</option>
                                    <option value="13px">13px</option>
                                    <option value="14px">14px</option>
                                    <option value="15px">15px</option>
                                    <option value="16px">16px</option>
                                </select>
                                <div class="pull-right">
                                    <span ng-show="lock.jsLock">Locked by:&nbsp;<b ng-bind="user"></b>&nbsp;</span>
                                    <div ng-show="lock.jsLock" class="glyphicon glyphicon-lock"></div>
                                    <div ng-show="dataChange.jsf" class="glyphicon glyphicon-floppy-save"></div>
                                </div>
                            </div>
                            <div class="panel-body dim-height" >
                                <div id="sidebar">
                                    <span id="position"></span>
                                    <div id="dragbar"></div>
                                    <div class ="list-group">                                        
                                        <a href="#" ng-click="jsLoad(name)" ng-class="{active: name===jsFileName}" ng-repeat="name in jsList" ng-bind="name" class="list-group-item"></a>
                                    </div>
                                </div>
                                <div id="main">
                                    <pre id="jsEditor"></pre>
                                </div>
                               
                            </div>

                        </div>
                    </tab>
                    <tab heading="Html">
                        <div class="panel panel-default margin-top">
                            <div class="panel-heading">

                                <input type="text" ng-model="htmlFileName" typeahead="name for name in htmlList" class="form-control" />
                                <div class="btn-group">
                                    <input class="btn btn-success" type="button" ng-click="htmlLoad(htmlFileName)" value="Load" />
                                    <input class="btn btn-success" type="button" value="Save" ng-click="saveHtmlFile()" />
                                    <input class="btn btn-success" type="button" value="Add File" ng-click="addFile(htmlFileName,'.html')" />
                                    <input class="btn btn-success" type="button" value="Remove File" ng-click="removeFile(htmlFileName, '.html')" />
                                </div>
                                <div class="pull-right">
                                    <span ng-show="lock.htmlLock">Locked by:&nbsp;<b ng-bind="user"></b>&nbsp;</span>
                                    <div ng-show="lock.htmlLock" class="glyphicon glyphicon-lock"></div>
                                    <div ng-show="dataChange.htmlf" class="glyphicon glyphicon-floppy-save"></div>
                                </div>
                            </div>
                            <div class="panel-body dim-height">
                                <pre id="htmlEditor"></pre>
                            </div>
                        </div>
                    </tab>
                    <tab heading="Css">
                        <div class="panel panel-default margin-top">
                            <div class="panel-heading">

                                <input type="text" ng-model="cssFileName" typeahead="name for name in cssList" class="form-control" />
                                <div class="btn-group">
                                    <input class="btn btn-success" type="button" ng-click="cssLoad(cssFileName)" value="Load" />
                                    <input class="btn btn-success" type="button" value="Save" ng-click="saveCssFile()" />
                                    <input class="btn btn-success" type="button" value="Add File" ng-click="addFile(cssFileName,'.css')" />
                                    <input class="btn btn-success" type="button" value="Remove File" ng-click="removeFile(cssFileName, '.css')" />
                                </div>
                                <div class="pull-right">
                                    <span ng-show="lock.cssLock">Locked by:&nbsp;<b ng-bind="user"></b>&nbsp;</span>
                                    <div ng-show="lock.cssLock" class="glyphicon glyphicon-lock"></div>
                                    <div ng-show="dataChange.cssf" class="glyphicon glyphicon-floppy-save"></div>
                                </div>
                            </div>
                            <div class="panel-body dim-height">
                                <pre id="cssEditor"></pre>
                            </div>
                        </div>
                    </tab>

                </tabset>
            </div>
        </div>
       
    </div>
    <div class="overlay">
        <div class="jwt-spinner"><b>Loading...</b></div>
    </div>
    <script type="text/ng-template" id="chatModal.html">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" ng-click="close()" aria-hidden="true">×</button>
            <b class="modal-title" ng-bind="sendto"></b>
        </div>
        <div class="modal-body messageArea">
            <div ng-repeat="u in list"><b ng-bind="u.sender+' : '"></b>&nbsp;<span ng-bind="u.message"></span></div>
        </div>
        <div class="modal-footer">
            <input class="form-control chat_inbox" ng-enter="send(message)" type="text" ng-model="message" />
            <button class="btn btn-primary" ng-click="send(message)">Send</button>

        </div>
    </script>
	<script src="../../Scripts/lib/jquery-2.1.1/jquery.min.js"></script>   
	<script src="../../Scripts/lib/angular/angular.min.js"></script>	
	<script src="../../Scripts/lib/angular/angular-resource.min.js"></script>
	<script src="../../Scripts/lib/angular/angularui.js"></script>
	<script src="../../Scripts/lib/angular/angular-ui-router.min.js"></script>     
    <script src="../../Scripts/lib/angular/angular-sanitize.min.js"></script>
    <script src="../../Scripts/lib/angular/angular-local-storage.min.js"></script>
	
    <script src="../../Scripts/tools/app.js"></script>
    <script src="../../Scripts/tools/jwt.js"></script>
    <script src="../../Scripts/tools/mainController.js"></script>
    <script src="../../Scripts/ace/ace.js"></script>
    <script src="../../Scripts/toastr/toastr.js"></script>
    <script src="../../Scripts/lib/bootstrap.min.js"></script>

</body>
</html>
