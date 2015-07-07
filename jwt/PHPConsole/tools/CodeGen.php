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
require_once('config.php');
class CodeGen
{
    
    private  $componentsCSS;
    public $app;
    public $root="";
    public $defaultNavigation="";

    public function __construct($jwtApp=null){
        $this->app=$jwtApp;
        $this->componentsCSS= new StringBuilder();
    }

    public function execute(){
        JwtUtil::makeDirectory($this->root."Scripts");
        JwtUtil::makeDirectory($this->root."Scripts/Components");
        JwtUtil::makeDirectory($this->root."Scripts/Layouts");

        $sb=new StringBuilder();
        //$sb->append('jukhan')->appendFormat(' full %s %s', 'jasim', 'uddin');
        
        //JwtUtil::makeDirectory("Scripts/Components/test2");
        //JwtUtil::putContent("Scripts/Components/test2/test2.js", $sb->toString());
        //$arr=JwtUtil::getSubDirectories("Scripts/Components");
        
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
                JwtUtil::makeDirectory( $this->root . "Scripts/Layouts/" . $layout->LayoutName);
                $sb->appendLine()
               ->append(TAB1)
                ->appendFormat("stateprovider.state('{0}'", $this->getStateName($layout))
                ->append(",{abstract:true,")
                ->appendFormat(@"url:'/{0}'", item.LayoutName);

                PathString = Root + string.Format("Scripts\\Layouts\\{0}\\{0}.html", item.LayoutName);
                if (!file.FileExists(PathString))
                {
                    file.Write(PathString, string.Format("<h3>Layout Name : {0}</h3><div ui-view></div>", item.LayoutName));
                }
                sb.Append(",templateUrl:" + GetTemplatePath(string.Format("'Scripts/Layouts/{0}/{0}.html'", item.LayoutName), item.LayoutName + "__LAYOUT__"));


                PathString = Root + string.Format("Scripts\\Layouts\\{0}\\{0}Ctrl.js", item.LayoutName);
                if (!file.FileExists(PathString))
                {
                    file.Write(PathString, getEmptyControllerForLayout(item.LayoutName));
                }
                sb.AppendFormat(",controller:'{0}Ctrl as vm'", item.LayoutName);
                layoutControllers.Add(item.LayoutName);

                sb.Append("});");
            }
    }
    private function setNavigation($sb){

    }
    private function genAllControllers(){

    }
    private function genAllServices(){

    }
    private function genAppDirectives(){

    }

     private function getTemplatePath($tentativePath, $wigenName)
     {
            if (JwtUtil::IsNullOrEmptyString($config["HasTemplateAuthorization"]))
            {
                return tentativePath;
            }
             $path = $config["HasTemplateAuthorization"];
            if (!JwtUtil::endsWith($path, "/"))
            {
                $path .= '/';
            }
            return sprintf( "'%s'", $path . $wigenName);

      }
      private function getStateName($layout)
      {
            $nameList =array();
            $nameList[]=$layout->LayoutName;
            while (!JwtUtil::IsNullOrEmptyString($layout->Extend))
            {
                $layout = $this->app->Layouts[$layout->Extend];
                $nameList[]=$layout->LayoutName;
            }
            return array_reverse($nameList);
        }
}
