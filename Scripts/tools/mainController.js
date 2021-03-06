﻿
angular.module('jwt2').controller('mainController', ['$scope', '$http', '$modal', '$q', '$timeout', 'localStorageService', function (scope, http, modal, qService, $timeout, localStorageService) {

    toastr.options.extendedTimeOut = 1000;
    toastr.options.timeOut = 1000;
    toastr.options.fadeOut = 250;
    toastr.options.fadeIn = 250;
    toastr.options.positionClass = "toast-top-right";

    scope.jsModel = '';
    scope.htmlModel = '';
    scope.jsList = [];
    scope.jsFileName = '';
    scope.htmlList = [];
    scope.htmlFileName = '';
    scope.cssList = [];
    scope.jsEditor = null;
    scope.htmlEditor = null;
    scope.theme = localStorageService.get('theme') || 'ace/theme/monokai';
    $timeout(function () { autoSize(); }, 1000);
    $timeout(function () {
        setJsEditor(scope);
        setHtmlEditor(scope);
        setCssEditor(scope);
        scope.setFont('13px');
    }, 1000);   
    $(window).resize(function () { autoSize(); });
    //new style
    scope.dataMode = '';
    scope.itemValue = '';
    scope.items = [];
    
    scope.setFont = function (fontSize) {
        document.getElementById('jsEditor').style.fontSize = fontSize;
        document.getElementById('htmlEditor').style.fontSize = fontSize;
        document.getElementById('cssEditor').style.fontSize = fontSize;
       
    };   
   
    scope.setTheme = function (theme) {
        scope.jsEditor.setTheme(theme);
        scope.htmlEditor.setTheme(theme);
        scope.cssEditor.setTheme(theme);
        localStorageService.set('theme', theme);
    };
    scope.changeDataMode = function (val) {
        if (val !== scope.dataMode) {
            unlocakAll();
            scope.items = [];
            scope.jsList = [];
            scope.htmlList = [];
            scope.cssList = [];
            if (val === 'Base') {
                scope.itemValue = '1';
                scope.dataMode = val;
                scope.changeItemValue();
                return;
            }
            http.get('GetItems/?name=' + val)
            .success(function (res) {
                scope.items = res;
                scope.itemValue = '0';
                setEditorDefault();

            })
        }
        scope.dataMode = val;
    }
    scope.changeItemValue = function () {
        if (scope.itemValue === '0') { return; }
        http.get('GetItemDetail/?name={0}&mode={1}'.format(scope.items[scope.itemValue], scope.dataMode))
        .success(function (res) {
            unlocakAll();
            scope.jsList = res.js;
            scope.htmlList = res.html;
            scope.cssList = res.css;
            setEditorDefault();
            if (res.js && res.js.length > 0) {
                scope.jsFileName = res.js[0];
                scope.jsLoad(scope.jsFileName);
            }
            if (res.html && res.html.length > 0)
                scope.htmlFileName = res.html[0];
            if (res.css && res.css.length > 0)
                scope.cssFileName = res.css[0];

        });
    };

    scope.addItem = function (name, mode) {
        if (!name) return
        createItem(name, mode);
    }
    function createItem(name, mode) {
        overlay(1);
        http.get('IsExist/?name={0}&mode={1}'.format(name, mode))
        .success(function (res) {
            if (!res.exist) {
                http.get('CreateItem/?name={0}&mode={1}'.format(name, mode))
                .success(function (res) {                    
                    success('Successfully generated.');                   
                    overlay(0);
                });
            }
            else {
                info('Already exist.');
                overlay(0);
            }
        });
    }
    function setEditorDefault() {
        scope.htmlEditor.setValue('');
        scope.jsEditor.setValue('');
        scope.cssEditor.setValue('');
        scope.jsFileName = '';
        scope.htmlFileName = '';
        scope.cssFileName = '';
    }
    function unlocakAll() {

        if (scope.dataChange.jsf) {
            saveFile(scope.dataMode, scope.items[scope.itemValue], scope.jsFileName, scope.jsEditor.getValue());
            scope.dataChange.jsf = false;
        }
        if (scope.dataChange.htmlf) {
            saveFile(scope.dataMode, scope.items[scope.itemValue], scope.htmlFileName, scope.htmlEditor.getValue());
            scope.dataChange.htmlf = false;
        }
        if (scope.dataChange.cssf) {
            saveFile(scope.dataMode, scope.items[scope.itemValue], scope.cssFileName, scope.cssEditor.getValue());
            scope.dataChange.cssf = false;
        }
    }
    function saveFile(mode, directoryName, fileName, content) {
        http.post('SaveFile', { mode: mode, directoryName: directoryName, fileName: fileName, content: content })
       .success(function (data) {
           if (data.isSuccess) {
               success('Saved successfully.');
              // jwtSvc.unlock({ UserName: jwtSvc.userName, Category: mode, Folder: directoryName, Name: fileName });
           }
       });
    }
    //end of new style

    //tab_javascript
    var previousFile=null;
    scope.jsLoad = function (fileName) {
        if (scope.dataChange.jsf) {            
            saveFile(scope.dataMode, scope.items[scope.itemValue], previousFile, scope.jsEditor.getValue());
            scope.dataChange.jsf = false;
            AWAIT.jsLock = 1; LOCK.jsLock = 0;
        }
        scope.jsFileName = fileName;
        previousFile=fileName;
        if (!fileName) { info('File name is required.'); return; }
        http.get('GetFileContent/?mode={0}&directoryName={1}&fileName={2}'.format(scope.dataMode, scope.items[scope.itemValue], fileName))
           .success(function (data) {
               AWAIT.jsLock = 0;
               scope.jsEditor.setValue(data.data);
               $timeout(function () { AWAIT.jsLock = 1; LOCK.jsLock = data.locked; }, 100);
               updateEditor(scope.jsFileName, data.locked, true);
               scope.jsEditor.moveCursorTo(0, 0);
           });

    }
   
    scope.saveJsFile = function () {
        if (!scope.jsFileName) { info('There is no file to be saved.'); return; }
        if (LOCK.jsLock) return;
        http.post('SaveFile', { mode: scope.dataMode, directoryName: scope.items[scope.itemValue]||'', fileName: scope.jsFileName, content: scope.jsEditor.getValue() })
        .success(function (data) {
            if (data.isSuccess) {
                //jwtSvc.unlock({ UserName: jwtSvc.userName, Category: scope.dataMode, Folder: scope.items[scope.itemValue], Name: scope.jsFileName });
                success('Saved successfully.');
                AWAIT.jsLock = 1;
                scope.dataChange.jsf = false; 
            }
        });
    }
    scope.addFile = function (fileName, ext) {
        //scope.jsFileName = fileName;
        if (!fileName) { info('File name is required.'); return; }
        http.get('IsFileExist/?mode={0}&directoryName={1}&fileName={2}&ext={3}'.format(scope.dataMode, scope.items[scope.itemValue], fileName, ext))
        .success(function (res) {
            if (res.exist) {
                info('Already exist.'); return;
            }

            http.get('AddFile/?mode={0}&directoryName={1}&fileName={2}&ext={3}'.format(scope.dataMode, scope.items[scope.itemValue], fileName, ext))
               .success(function (data) {
                   if (data.isSuccess) {
                       success(data.msg);
                       if (ext === '.js') {
                           scope.jsList.push(fileName);
                           scope.jsLoad(fileName);
                       }
                       if (ext === '.html') { scope.htmlList.push(fileName); scope.htmlLoad(fileName); }
                       if (ext === '.css') { scope.cssList.push(fileName); scope.cssLoad(fileName); }
                   }
                   else {
                       info(data.msg);
                   }
               });
        });
    }
    scope.removeFile = function (fileName, ext) {
        //scope.jsFileName = fileName;
        if (!fileName) { info('File name is required.'); return; }

        http.get('IsFileExist/?mode={0}&directoryName={1}&fileName={2}&ext={3}'.format(scope.dataMode, scope.items[scope.itemValue], fileName, ext))
        .success(function (res) {
            if (!res.exist) {
                info('File is not exist.'); return;
            }
            if (confirm('Are you sure you want to remove this file?')) {
                http.get('RemoveFile/?mode={0}&directoryName={1}&fileName={2}&ext={3}'.format(scope.dataMode, scope.items[scope.itemValue], fileName, ext))
                   .success(function (data) {
                       if (data.isSuccess) {
                           success(data.msg);
                           setEditorDefault();
                           if (ext === '.js') { scope.jsList.remove(function (fn) { return fn === fileName; }); }
                           if (ext === '.html') { scope.htmlList.remove(function (fn) { return fn === fileName; }); }
                           if (ext === '.js') { scope.cssList.remove(function (fn) { return fn === fileName; }); }
                       }
                       else {
                           info(data.msg);
                       }
                   });
            }
        });
    }
    //tab_html

    scope.htmlLoad = function (fileName) {
        scope.htmlFileName = fileName;
        if (!fileName) { info('File name is required.'); return; }
        http.get('GetFileContent/?mode={0}&directoryName={1}&fileName={2}'.format(scope.dataMode, scope.items[scope.itemValue], fileName))
           .success(function (data) {
               AWAIT.htmlLock = 0;
               scope.htmlEditor.setValue(data.data);
               $timeout(function () { AWAIT.htmlLock = 1; LOCK.htmlLock = data.locked; }, 100);
               updateEditor(scope.htmlFileName, data.locked, true);
               scope.htmlEditor.moveCursorTo(0, 0);
           });

    }
    scope.saveHtmlFile = function () {
        if (!scope.htmlFileName) { info('There is no file to be saved.'); return; }
        if (LOCK.htmlLock) return;
        http.post('SaveFile', { mode: scope.dataMode, directoryName: scope.items[scope.itemValue], fileName: scope.htmlFileName, content: scope.htmlEditor.getValue() })
        .success(function (data) {
            if (data.isSuccess) {
                //jwtSvc.unlock({ UserName: jwtSvc.userName, Category: scope.dataMode, Folder: scope.items[scope.itemValue], Name: scope.htmlFileName });
                success('Saved successfully.');
                AWAIT.htmlLock = 1;
                scope.dataChange.htmlf = false;
            }
        });
    }

    //tab_css

    scope.cssLoad = function (fileName) {
        scope.cssFileName = fileName;
        if (!fileName) { info('File name is required.'); return; }
        http.get('GetFileContent/?mode={0}&directoryName={1}&fileName={2}'.format(scope.dataMode, scope.items[scope.itemValue], fileName))
           .success(function (data) {
               AWAIT.cssLock = 0;
               scope.cssEditor.setValue(data.data);
               $timeout(function () { AWAIT.cssLock = 1; LOCK.cssLock = data.locked; }, 100);
               updateEditor(scope.cssFileName, data.locked, true);
               scope.cssEditor.moveCursorTo(0, 0);
           });

    }
    scope.saveCssFile = function () {
        if (!scope.cssFileName) { info('There is no file to be saved.'); return; }
        if (LOCK.cssLock) return;
        http.post('SaveFile', { mode: scope.dataMode, directoryName: scope.items[scope.itemValue], fileName: scope.cssFileName, content: scope.cssEditor.getValue() })
        .success(function (data) {
            if (data.isSuccess) {
                //jwtSvc.unlock({ UserName: jwtSvc.userName, Category: scope.dataMode, Folder: scope.items[scope.itemValue], Name: scope.cssFileName });
                success('Saved successfully.');
                AWAIT.cssLock = 1;
                scope.dataChange.cssf = false;
            }
        });
    }
    //signalR

    var LOCK = { jsLock: 0, htmlLock: 0, cssLock: 0 };
    var AWAIT = { jsLock: 0, htmlLock: 0, cssLock: 0 };
    scope.lock = LOCK;
    scope.dataChange = { jsf: 0, htmlf: 0, cssf: 0 };
    scope.users = [];
    scope.user = "";
    scope.$on('newConnection', function (event, user) {

        info(user + ' Joined in Development');
        scope.users.remove(function (user2) { return user2 === user; });
        scope.users.push(user);
        scope.$apply();
    });
    scope.$on('removeConnection', function (event, user) {
        info(user + ' Disconnected from Development');
        scope.users.remove(function (user2) { return user2 === user; });
        scope.$apply();
    });
    scope.$on('onlineUsers', function (event, users) {
        scope.users = users;

        scope.$apply();
    });
    scope.$on('lockFile', function (event, file) {
        var folder = scope.items[scope.itemValue];
        if (scope.dataMode === file.Category) {
            scope.user = file.UserName;
            if (file.Category === 'Base') {
                updateEditor(file.Name, true, false);
            }
            else if (file.Folder === folder) {
                updateEditor(file.Name, true, false);
            }
        }
    });
    scope.$on('unlockFile', function (event, file) {
        var folder = scope.items[scope.itemValue];
        if (scope.dataMode === file.Category) {
            if (file.Category === 'Base') {
                updateEditor(file.Name, false, false);
            }
            else if (file.Folder === folder) {
                updateEditor(file.Name, false, false);
            }
        }
    });
    scope.jsChange = function () {
        if (AWAIT.jsLock) {
            if (scope.jsEditor.getValue()) {
                //try { jwtSvc.lock({ UserName: jwtSvc.userName, Category: scope.dataMode, Folder: scope.items[scope.itemValue] || 'base', Name: scope.jsFileName }); } catch (error) { }
                AWAIT.jsLock = 0;
                scope.$apply(function () { scope.dataChange.jsf = true; });
                //scope.dataChange.jsf = true;
            }
        }
    };
    scope.htmlChange = function () {
        if (AWAIT.htmlLock) {
            if (scope.htmlEditor.getValue()) {
                //try { jwtSvc.lock({ UserName: jwtSvc.userName, Category: scope.dataMode, Folder: scope.items[scope.itemValue], Name: scope.htmlFileName }); } catch (error) { }
                AWAIT.htmlLock = 0;
                scope.$apply(function () { scope.dataChange.htmlf = true; });
                //scope.dataChange.htmlf = true;
            }
        }
    };
    scope.cssChange = function () {
        if (AWAIT.cssLock) {
            if (scope.cssEditor.getValue()) {
                //try { jwtSvc.lock({ UserName: jwtSvc.userName, Category: scope.dataMode, Folder: scope.items[scope.itemValue], Name: scope.cssFileName }); } catch (error) { }
                AWAIT.cssLock = 0;
                scope.$apply(function () { scope.dataChange.cssf = true; });
                 //scope.dataChange.cssf = true;
            }
        }
    };
    function updateEditor(fileName, readOnly, isFromLoadContent) {

        if (fileName === scope.jsFileName) {
            scope.jsEditor.setReadOnly(readOnly);
            if (!isFromLoadContent)
               LOCK.jsLock = readOnly;                
            if (!LOCK.jsLock && !isFromLoadContent) { scope.jsLoad(fileName); info('Unlocked ' + fileName); }
        }
        else if (fileName === scope.htmlFileName) {
            scope.htmlEditor.setReadOnly(readOnly);
            if (!isFromLoadContent)
                LOCK.htmlLock = readOnly; 
            if (!LOCK.htmlLock && !isFromLoadContent) { scope.htmlLoad(fileName); info('Unlocked ' + fileName); }
        }
        else if (fileName === scope.cssFileName) {
            scope.cssEditor.setReadOnly(readOnly);
            if (!isFromLoadContent)
                LOCK.cssLock = readOnly; 
            if (!LOCK.cssLock && !isFromLoadContent) { scope.cssLoad(fileName); info('Unlocked ' + fileName); }
        }

    }
    //end signalR
    //online user
    var modalInstance = null, chatUser = '';
    scope.messageList = [];
    //jwtSvc.connectionDone.then(function () {
    //    http.get('jwtEx/GetHostPath').success(function (res) {
    //        jwtSvc.initHub(jwtSvc.userName, res.path);
    //    });
        
    //});
    scope.$on('receiveMessage', function (event, data) {
        scope.messageList[data.sender] = scope.messageList[data.sender] || [];
        scope.messageList[data.sender].push(data);
        scope.$apply();
        if (chatUser !== data.sender) {
            if (modalInstance)
                modalInstance.close();
            //openPopup(jwtSvc.userName, data.sender, scope.messageList[data.sender]);
            chatUser = data.sender;
        } else {
            modalInstance.close();
            //openPopup(jwtSvc.userName, data.sender, scope.messageList[data.sender]);
        }

    });
    scope.toggle = false;
    scope.onclick = function () {
        scope.toggle = !scope.toggle;
    };
    scope.showPopup = function (user) {

        chatUser = user;
        scope.messageList[user] = scope.messageList[user] || [];
        //openPopup(jwtSvc.userName, user, scope.messageList[user]);
    };
    //lg,sm
    function openPopup(sender, sendto, list) {
        modalInstance = modal.open({
            templateUrl: 'chatModal.html',
            controller: 'ModalInstanceCtrl',
            backdrop: 'static',
            size: 'lg',
            resolve: {
                data: function () {
                    return { sender: sender, sendto: sendto, list: list };
                }
            }
        });
        $timeout(function () { scrollTop() }, 500);
    }
    //end online user
    function success(msg) {
        toastr['success'](msg);
    }
    function info(msg) {
        toastr['info'](msg);
    }
    $timeout(function () { splitter(); }, 1000);
   
}]);

function scrollTop() {
    var messageArea = $('.messageArea');
    if (messageArea.length > 0) {
        var top = messageArea.get(0).scrollHeight;
        messageArea.scrollTop(top);
    }
}
function setJsEditor(scope) {   
    scope.jsEditor = ace.edit("jsEditor");    
    scope.jsEditor.setOptions({
        theme: scope.theme,
        mode: "ace/mode/javascript",
        autoScrollEditorIntoView: true
    });
    scope.jsEditor.$blockScrolling = Infinity;
    scope.jsEditor.getSession().on('change', function (e) {
        scope.jsChange();
    });   
    scope.jsEditor.commands.addCommand({
        name: 'save',
        bindKey: { win: 'Ctrl-S', mac: 'Command-S' },
        exec: function (env, args, request) {
            scope.saveJsFile();
        }
    });
}
function setHtmlEditor(scope) {   
    scope.htmlEditor = ace.edit("htmlEditor");
    scope.htmlEditor.setOptions({
        theme: scope.theme,
        mode: "ace/mode/html",
        autoScrollEditorIntoView: true
    });
    scope.htmlEditor.$blockScrolling = Infinity;
    scope.htmlEditor.getSession().on('change', function (e) {
        scope.htmlChange();
    });
    scope.htmlEditor.commands.addCommand({
        name: 'save',
        bindKey: { win: 'Ctrl-S', mac: 'Command-S' },
        exec: function (env, args, request) {
            scope.saveHtmlFile();
        }
    });
}
function setCssEditor(scope) { 
    scope.cssEditor = ace.edit("cssEditor");
    scope.cssEditor.setOptions({
        theme: scope.theme,
        mode: "ace/mode/css",
        autoScrollEditorIntoView: true
    });
    scope.cssEditor.$blockScrolling = Infinity;
    scope.cssEditor.getSession().on('change', function (e) {
        scope.cssChange();
    });
    scope.cssEditor.commands.addCommand({
        name: 'save',
        bindKey: { win: 'Ctrl-S', mac: 'Command-S' },
        exec: function (env, args, request) {
            scope.saveCssFile();
        }
    });
    ////For All
    ace.config.loadModule("ace/ext/emmet", function () {
        ace.require("ace/lib/net").loadScript("http://cloud9ide.github.io/emmet-core/emmet.js", function () {
            scope.jsEditor.setOption("enableEmmet", true);
            scope.htmlEditor.setOption("enableEmmet", true);
            scope.cssEditor.setOption("enableEmmet", true);
        });
    });

    ace.config.loadModule("ace/ext/language_tools", function () {
        scope.jsEditor.setOptions({
            enableSnippets: true,
            enableBasicAutocompletion: true
        });
        scope.htmlEditor.setOptions({
            enableSnippets: true,
            enableBasicAutocompletion: true
        });
        scope.cssEditor.setOptions({
            enableSnippets: true,
            enableBasicAutocompletion: true
        });
    });
}
function overlay(val) {
    val ? $('.overlay').show() : $('.overlay').hide();
}

function autoSize() {
    var dim = $('.dim-height');
    var height = $(window).height() - dim.position().top - 50;
    dim.css('height', height);
    $('#jsEditor, #htmlEditor, #cssEditor').css('height', height - 6);
    $('#dragbar,#ghostbar').css('height', height);
}

function splitter() {
    var i = 0;
    var dragging = false;
    $('#dragbar').mousedown(function (e) {
        e.preventDefault();

        dragging = true;
        var main = $('#main');
        var ghostbar = $('<div>',
                         {
                             id: 'ghostbar',
                             css: {
                                 height: main.outerHeight(),
                                 top: main.offset().top,
                                 left: main.offset().left
                             }
                         }).appendTo('body');

        $(document).mousemove(function (e) {
            ghostbar.css("left", e.pageX + 2);
        });
    });

    $(document).mouseup(function (e) {
        if (dragging) {
            $('#sidebar').css("width", e.pageX + 2);
            $('#main').css("left", e.pageX + 2);
            $('#ghostbar').remove();
            $(document).unbind('mousemove');
            dragging = false;
        }
    });

}