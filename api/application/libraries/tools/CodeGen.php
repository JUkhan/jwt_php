<?php

/**
 * CodeGen short summary.
 *
 * CodeGen description.
 *
 * @version 1.0
 * @author Jasim.Uddin
 */
require_once('JwtUtil.php');
require_once('StringBuilder.php');

class CodeGen
{
    //private $navList;
    private $mControllers;
    private $layoutControllers;
    private  $componentsCSS;
    public $app;
    public $root="./";
    public $defaultNavigation="";
    public $has_template_authorization="";

    public function __construct($jwtApp=null){
        $this->app=$jwtApp;
        $this->componentsCSS= new StringBuilder();
        $this->layoutControllers=array();
        $this->mControllers=array();
        
    }
    public function CreateItem($name, $mode)
    {
       
        JwtUtil::makeDirectory($this->root."Scripts");
        JwtUtil::makeDirectory($this->root."Scripts/Components");
        JwtUtil::makeDirectory($this->root."Scripts/Directives");
        JwtUtil::makeDirectory($this->root."Scripts/Modules");

        $path = $this->root;
        switch ($mode)
        {
            case "Widgets":
                $path .= "Scripts/Components/" . $name;                
                JwtUtil::makeDirectory($path); 
                $path = $this->root . "Scripts/Components/$name/$name"."Ctrl.js";
                JwtUtil::putContent($path, $this->getEmptyController($name));
                $path = $this->root .  "Scripts/Components/$name/$name" . "Svc.js";
                JwtUtil::putContent($path, $this->getEmptyService($name));
                $path = $this->root . "Scripts/Components/$name/$name" . ".html";
                JwtUtil::putContent($path, "<h3>widget Name : {{vm.title}}</h3>");

                break;
            case "Components":
                $path .= "Scripts/Directives/" . $name;
                JwtUtil::makeDirectory($path);
                $path =  $this->root ."Scripts/Directives/$name/$name" . ".js";
                JwtUtil::putContent($path, $this->getEmptyDirective($name));
                $path =  $this->root ."Scripts/Directives/$name/$name" . ".html";
                JwtUtil::putContent($path, "<b>Hello world</b>");
                $path =  $this->root . "Scripts/Directives/$name/$name" . ".css";
                JwtUtil::putContent($path, "/*css goes here*/");
                break;
            case "Modules":
                if ($name == "app") return;
                $path .= "Scripts/Modules/" . $name;
                JwtUtil::makeDirectory($path);
                $path = $this->root . "Scripts/Modules/$name/$name" . ".js";
                JwtUtil::putContent($path, $this->getEmptyModule($name));
                break;
        }
        return " mamma kam oia geche";
    }
    public function execute(){
        JwtUtil::makeDirectory($this->root."Scripts");
        JwtUtil::makeDirectory($this->root."Scripts/Components");
        JwtUtil::makeDirectory($this->root."Scripts/Layouts");

        $sb=new StringBuilder();       
        
        $sb->appendLine()
        ->append("export default function config(stateprovider, routeProvider){");
        if(!JwtUtil::IsNullOrEmptyString($this->defaultNavigation))
        {
            $sb->appendLine()->appendTab()
            ->appendFormat( "routeProvider.otherwise('%s');", $this->defaultNavigation)
            ->appendLine();
        }
        $this->setLayout($sb);
        $this->setNavigation($sb);
        $sb->appendLine()
        ->append("}")
        ->appendLine()
        ->append("config.\$inject=['\$stateProvider', '\$urlRouterProvider'];")
        ->appendLine();
        JwtUtil::putContent($this->root . "Scripts/config.js", $sb->toString());


        $this->genAllControllers();
        $this->genAllServices();
        $this->genAppDirectives();
        $dir = JwtUtil::getSubDirectories($this->root . "Scripts/Modules");
        foreach ($dir as $name)
        {
            if (JwtUtil::fileExists($this->root. "Scripts/Modules/$name/$name.css", $name, $name))
            {
                $this->componentsCSS->append("@import '../Scripts/Modules/$name/$name.css';")
                 ->appendLine();
            }
        }
        JwtUtil::putContent($this->root . "Content/components.css", $this->componentsCSS->toString());
    }
    private function setLayout($sb){       
        
        foreach ($this->app->Layouts as  $layout)
        {
            JwtUtil::makeDirectory( $this->root . "Scripts/Layouts/" . $layout['LayoutName']);
            $sb->appendLine()
            ->appendTab()
            ->appendFormat("stateprovider.state('%s'", $this->getStateName($layout))
            ->append(",{abstract:true,")
            ->appendFormat("url:'/%s'", $layout['LayoutName']);

            $PathString =$this->root . "Scripts/Layouts/".$layout['LayoutName']."/".$layout['LayoutName'].".html";
            if (!JwtUtil::fileExists($PathString))
            {
                JwtUtil::putContent($PathString, "<h3>Layout Name :". $layout['LayoutName']. "</h3><div ui-view></div>");
            }
            $sb->append(",templateUrl:" . $this->getTemplatePath("'Scripts/Layouts/".$layout['LayoutName']."/".$layout['LayoutName'].".html'", $layout['LayoutName'] . "__LAYOUT__"));
            
            $PathString = $this->root . sprintf("Scripts/Layouts/%s/%sCtrl.js", $layout['LayoutName'],$layout['LayoutName']);
            if (!JwtUtil::fileExists($PathString))
            {
                JwtUtil::putContent($PathString, $this->getEmptyControllerForLayout($layout['LayoutName']));
            }
            $sb->appendFormat(",controller:'%sCtrl as vm'", $layout['LayoutName']);
            $this->layoutControllers[]=$layout['LayoutName'];

            $sb->append("});");
        }
    }
    private function getParamName($p)
    {       
        if (JwtUtil::startsWith($p,"/:")){ return $p;}
        if (JwtUtil::startsWith($p,":")) return '/' . $p;
        return "/:" . $p;
    }
    private function setNavigation($sb){
        
        $sb->appendLine();
        if(!isset($this->app->Navigations)){return;}
        foreach ($this->app->Navigations as  $item)
        {
            $createNew = false;
            if (!JwtUtil::folderExists( $this->root . "Scripts/Components/" . $item['WidgetName']))
            {
                $createNew = true;
            }
            JwtUtil::makeDirectory($this->root . "Scripts/Components/" . $item['WidgetName']);
            $sb->appendLine()->appendTab();                
            $sb->appendFormat("stateprovider.state('%s'", $this->getNavigationStateName($item));
            $sb->append(",{");
            $sb->appendFormat("url:'/%s%s'", $item['NavigationName'], JwtUtil::IsNullOrEmptyString($item['ParamName']) ? "" : $this->getParamName($item['ParamName']));

            $view = $item['Views'];
            if (isset($view) && count($view)>0)
            {
                $sb->append(",views:{");
                $isFirst = true;
                foreach ($view as $item2)
                {
                    if ($isFirst)
                        $sb->append("'" . $item2['ViewName'] . "':{");
                    else
                        $sb->append(",'" . $item2['ViewName'] . "':{");

                    if (!JwtUtil::IsNullOrEmptyString($item2['WidgetName']))
                    {
                        $PathString = $this->root . sprintf("Scripts/Components/%s/%s.html", $item2['WidgetName'],$item2['WidgetName']);
                        if (!JwtUtil::fileExists($PathString))
                        {
                            JwtUtil::putContent($PathString, "<h3>widget Name : {{vm.title}}</h3>");
                        }
                        $sb->append("templateUrl:" . $this->getTemplatePath(sprintf("'Scripts/Components/%s/%s.html'", $item2['WidgetName'],$item2['WidgetName']), $item2['WidgetName']));
                        $this->mControllers[]=$item2['WidgetName'];

                        $PathString = $this->root . sprintf("Scripts/Components/%s/%sCtrl.js", $item2['WidgetName'], $item2['WidgetName']);
                        if (JwtUtil::fileExists($PathString))
                        {
                            $sb->appendFormat(",controller:'%sCtrl as vm'", $item2['WidgetName']);
                        }

                    }
                    $sb->append("}");
                    $isFirst = false;
                }
                $sb->append("}");
            }
            if (!JwtUtil::IsNullOrEmptyString($item['WidgetName']))
            {
                if ($createNew)
                {
                    $PathString = $this->root . sprintf("Scripts/Components/%s/%s.html", $item['WidgetName'], $item['WidgetName']);
                    JwtUtil::putContent($PathString, "<h3>widget Name : {{vm.title}}</h3>");
                    $PathString = $this->root . sprintf("Scripts/Components/%s/%sCtrl.js",  $item['WidgetName'],  $item['WidgetName']);
                    JwtUtil::putContent($PathString, $this->getEmptyController($item['WidgetName']));
                    $PathString = $this->root . sprintf("Scripts/Components/%s/%sSvc.js",  $item['WidgetName'], $item['WidgetName']);
                    JwtUtil::putContent($PathString,$this->getEmptyService($item['WidgetName']));
                }
                $PathString =$this->root . sprintf("Scripts/Components/%s/%s.html",  $item['WidgetName'], $item['WidgetName']);
                if (JwtUtil::fileExists($PathString))
                {
                    $sb->append(",templateUrl:" . $this->getTemplatePath(sprintf("'Scripts/Components/%s/%s.html'",  $item['WidgetName'], $item['WidgetName']),  $item['WidgetName']));
                }
                $PathString = $this->root . sprintf("Scripts/Components/%s/%sCtrl.js",  $item['WidgetName'], $item['WidgetName']);
                if (JwtUtil::fileExists($PathString))
                {
                    $sb->appendFormat(",controller:'%sCtrl as vm'",  $item['WidgetName']);
                }
                $this->mControllers[]=$item['WidgetName'];
            }

            $sb->Append("});");
        }
    }
    private function genAllControllers(){
        $list = array_unique($this->mControllers);
        $sb = new StringBuilder();
        $directoryName = "Components";
        foreach ($list as $item)
        {
            if (JwtUtil::fileExists($this->root .sprintf("Scripts/Components/%s/%sCtrl.js", $item, $item)))
            {
                $sb->AppendLine();
                $sb->AppendFormat("import %s from 'Scripts/%s/%s/%sCtrl.js';", $item, $directoryName,  $item, $item);
            }
        }
        $list =array_unique($this->layoutControllers);
        $directoryName = "Layouts";
        foreach ($list as $item)
        {
            if (JwtUtil::fileExists($this->root . sprintf("Scripts/Layouts/%s/%sCtrl.js", $item, $item)))
            {
                $sb->appendLine();
                $sb->appendFormat("import %s from 'Scripts/%s/%s/%sCtrl.js';", $item, $directoryName, $item, $item);
            }
        }
        $sb->appendLine();
        $sb->appendLine();
        $sb->appendFormat("var moduleName='%s.controllers';", $this->app->Name);
        $sb->appendLine();
        $sb->appendLine();
        $sb->append("angular.module(moduleName,[])");
        $list = array_unique($this->mControllers);
        foreach ($list as $item)
        {
            if (JwtUtil::fileExists($this->root . sprintf("Scripts/Components/%s/%sCtrl.js", $item, $item)))
            {
                $sb->appendLine();
                $sb->appendFormat(".controller('%sCtrl', %s)", $item, $item);
            }
            if (JwtUtil::fileExists(sprintf($this->root . "Scripts/Components/%s/%s.css", $item, $item)))
            {
                $this->componentsCSS->appendFormat("@import '../Scripts/Components/%s/%s.css';", $item, $item);
                $this->componentsCSS->appendLine();
            }
        }
        $list = array_unique($this->layoutControllers);
        foreach ($list as $item)
        {
            if (JwtUtil::fileExists($this->root . sprintf("Scripts/Layouts/%s/%sCtrl.js", $item, $item)))
            {
                $sb->appendLine();
                $sb->appendFormat(".controller('%sCtrl', %s)", $item, $item);
            }
            if (JwtUtil::fileExists(sprintf($this->root . "Scripts/Layouts/%s/%s.css", $item, $item)))
            {
                $this->componentsCSS->appendFormat("@import '../Scripts/Layouts/%s/%s.css';", $item, $item);
                $this->componentsCSS->appendLine();
            }
        }
        $sb->append(";");
        $sb->appendLine();
        $sb->appendLine();
        $sb->append("export default moduleName;");
        JwtUtil::putContent($this->root . "Scripts/app.controllers.js", $sb->toString());
    }
    private function genAllServices(){
        $list = array_unique($this->mControllers);
        $sb = new StringBuilder();
        $directoryName = "Components";
        foreach ($list as $item)
        {
            if (JwtUtil::fileExists($this->root . sprintf("Scripts/Components/%s/%sSvc.js", $item,$item)))
            {
                $sb->appendLine();
                $sb->appendFormat("import %s from 'Scripts/%s/%s/%sSvc.js';", $item, $directoryName,$item,$item);
            }
        }
        $list =array_unique($this->layoutControllers);
        $directoryName = "Layouts";
        foreach ($list as $item)
        {
            if (JwtUtil::fileExists($this->root . sprintf("Scripts/Layouts/%s/%sSvc.js", $item, $item)))
            {
                $sb->appendLine();
                $sb->appendFormat("import %s from 'Scripts/%s/%s/%sSvc.js';", $item, $directoryName,$item,$item);
            }
        }
        $sb->appendLine();
        $sb->appendLine();
        $sb->appendFormat("var moduleName='%s.services';", $this->app->Name);
        $sb->appendLine();
        $sb->appendLine();
        $sb->append("angular.module(moduleName,[])");
        $list =array_unique($this->mControllers);
        foreach ($list as $item)
        {
            if (JwtUtil::fileExists($this->root . sprintf("Scripts/Components/%s/%sSvc.js", $item, $item)))
            {
                $sb->appendLine();
                $sb->appendFormat(".factory('%sSvc', %s)", $item, $item);
            }
        }
        $list =array_unique($this->layoutControllers);
        foreach ($list as $item)
        {
            if (JwtUtil::fileExists($this->root . sprintf("Scripts/Layouts/%s/%sSvc.js", $item, $item)))
            {
                $sb->appendLine();
                $sb->appendFormat(".factory('%sSvc', %s)", $item, $item);
            }
        }
        $sb->append(";");
        $sb->appendLine();
        $sb->appendLine();
        $sb->append("export default moduleName;");
        JwtUtil::putContent($this->root . "Scripts/app.services.js", $sb->toString());
    }
    private function genAppDirectives(){
        $dir = JwtUtil::getSubDirectories($this->root . "Scripts/Directives");
        $import1 = new StringBuilder();
        $builder = new StringBuilder();

        foreach ($dir as $item)
        {
            $import1->appendFormat("import %s from 'Scripts/Directives/%s/%s.js';", $item,$item,$item);
            $import1->appendLine();

            $builder->appendFormat(".directive('%s', %s.builder)", $item, $item);
            $builder->appendLine();
            if (JwtUtil::fileExists(sprintf($this->root. "Scripts/Directives/%s/%s.css", $item, $item)))
            {
                $this->componentsCSS->appendFormat("@import '../Scripts/Directives/%s/%s.css';", $item, $item);
                $this->componentsCSS->appendLine();
            }
        }

        $res = new StringBuilder();
        $res->append($import1->toString());
        $res->appendLine();
        $res->appendLine();
        $res->appendFormat("var moduleName='%s.Directives';", $this->app->Name);
        $res->appendLine();
        $res->appendLine();
        $res->append("angular.module(moduleName, [])");
        $res->appendLine();
        $res->append($builder->toString());
        $res->append(";");
        $res->appendLine();
        $res->appendLine();
        $res->append("export default moduleName;");
        JwtUtil::putContent($this->root . "Scripts/app.directives.js", $res->toString());
    }
    private function getEmptyModule($name)
        {
            $res = new StringBuilder();

             $res->append("//import sample from 'Scripts/Modules/$name/sample.js';");
             $res->appendLine();
             $res->appendLine();
             $res->append("var moduleName='$name'; ");
             $res->appendLine();
             $res->append("angular.module(moduleName, []);");
             $res->appendLine();
             $res->append("export default moduleName;");
             $res->appendLine();
            return  $res->toString();
        }
    private function getEmptyController($name)
    {
        $sb = new StringBuilder();
        $sb->append("import BaseCtrl from 'Scripts/Base/BaseCtrl.js';");
        $sb->appendLine();
        
        $sb->appendLine();
        $sb->appendFormat("class %sCtrl extends BaseCtrl", $name);
        $sb->appendLine();
        $sb->append("{");
        $sb->appendLine()->appendTab()->append("constructor(scope, svc){");
        $sb->appendLine()->appendTab2()->append( "super(scope);");
        
        $sb->appendLine()->appendTab2()->append("this.svc = svc;");
        $sb->appendLine()->appendTab2()->appendFormat("this.title='%s';", $name);
        $sb->appendLine()->appendTab()->append("}");
        $sb->appendLine();
        $sb->append("}");
        $sb->appendLine()->appendFormat( "%sCtrl.\$inject=['\$scope', '%sSvc'];", $name, $name);
        $sb->appendLine()->appendFormat( "export default %sCtrl;", $name);
        return $sb->toString();
    }
    private function getEmptyControllerForLayout($name){
        $sb = new StringBuilder();

        $sb->appendFormat("class %sCtrl", $name);
        $sb->appendLine();
        $sb->append("{");
        $sb->appendLine()->appendTab()->append("constructor(){");
        $sb->appendLine()->appendTab2()->appendFormat("this.title='%s';", $name);
        $sb->appendLine()->appendTab()->append("}");
        $sb->appendLine();
        $sb->append("}");
        $sb->appendLine()->appendFormat("export default %sCtrl;", $name);
        return $sb->toString();
    }
    private function getEmptyService($name)
    {
        $sb = new StringBuilder();
        $sb->append("import BaseSvc from 'Scripts/Base/BaseSvc.js';");
        $sb->appendLine();
        
        $sb->appendLine();
        $sb->appendFormat("class %sSvc extends BaseSvc", $name);
        $sb->appendLine();
        $sb->append("{");
        $sb->appendLine()->appendTab()->append("constructor(http){");
        $sb->appendLine()->appendTab2()->append("super(http);");
        
        $sb->appendLine()->appendTab2()->append("this.http= http;");
        $sb->appendLine()->appendTab()->append("}");
        $sb->appendLine();
        $cname = ucfirst($name);
        $sb->appendTab()->appendFormat("static %sFactory(http)", $cname);
        $sb->appendTab()->append( "{");
        $sb->appendLine();
        $sb->appendTab2()->appendFormat( "return new %sSvc(http);", $name);
        $sb->appendLine()->appendTab()->append( "}");
        $sb->appendLine()->append("}");
        $sb->appendLine()->appendFormat( "%sSvc.%sFactory.\$inject=['\$http'];", $name, $cname);
        $sb->appendLine()->appendFormat( "export default %sSvc.%sFactory;", $name, $cname);
        return $sb->toString();
    }
    public function  getEmptyDirective($name)
    {
            $sb = new StringBuilder();
            $sb->append("class " . $name);
            $sb->appendLine();
            $sb->append("{");
            $sb->appendLine()->appendTab()->append("constructor(){");
            $sb->appendLine()->appendTab2()->append( "this.restrict='E';");
            $sb->appendLine()->appendTab2()->append("this.templateUrl='Scripts/Directives/$name/$name.html';");
            $sb->appendLine()->appendTab()->append("}");
            $sb->appendLine();

            $sb->appendTab()->append( "static builder()");
            $sb->appendTab()->append( "{");
            $sb->appendLine();
            $sb->appendTab2()->append("return new $name();");
            $sb->appendLine();
            $sb->appendTab()->append( "}");
            $sb->appendLine();
            $sb->append("}");
            $sb->appendLine()->append("export default $name;");
            return $sb->toString();
    }
    private function getTemplatePath($tentativePath, $wigenName)
    {
        if (JwtUtil::IsNullOrEmptyString($this->has_template_authorization))
        {
            return $tentativePath;
        }
        $path =$this->has_template_authorization;
        if (!JwtUtil::endsWith($path, "/"))
        {
            $path .= '/';
        }
        return sprintf( "'%s'", $path . $wigenName);

    }
    private function array_find($arr, $fieldName, $layoutName){
        foreach($arr as $item){
            if($item[$fieldName]==$layoutName){                
                return $item;
            }
        }
        return null;
    }
   
    private function getStateName($layout)
    {
       
        $nameList =array();  
        $nameList[]=$layout['LayoutName'];
       
        while (isset($layout) && !JwtUtil::IsNullOrEmptyString($layout['Extend']))
        {            
            $layout =$this->array_find($this->app->Layouts, 'LayoutName', $layout['Extend']);             
            if(isset($layout)){
                $nameList[]=$layout['LayoutName'];               
            }
        }
       
        return implode(".", array_reverse($nameList));
    }
    private function getNavigationStateName($navigation)
    {
        $nameList = array();
       
        $nameList[]=$navigation['NavigationName'];
        $layout = null;
        if (!JwtUtil::IsNullOrEmptyString($navigation['HasLayout']))
        {            
            $layout =$this->array_find($this->app->Layouts, 'LayoutName', $navigation['HasLayout']);
            if(isset($layout)){
                    $nameList[]=$layout['LayoutName'];
                    while (!JwtUtil::IsNullOrEmptyString($layout['Extend']))
                    {
                       $layout =$this->array_find($this->app->Layouts, 'LayoutName', $layout['Extend']); 
                       if(isset($layout)){
                            $nameList[]=$layout['LayoutName'];
                        }
                    }
            }
        }       
        return implode(".", array_reverse($nameList));
        
    }

}
