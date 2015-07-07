<?php

/**
 * Navigation short summary.
 *
 * Navigation description.
 *
 * @version 1.0
 * @author Jasim.Uddin
 */
class Navigation
{
    public  $_id ;
    public  $NavigationName ;
    public  $HasLayout;
    public  $ParamName ;
    public  $WidgetName ;
    private $_views =array();
    public $UIViews=array();
    public function GetView() { return $this->_views; }
    public function setView($view)
    {        
        if (!isset($this->_views[$view->ViewName]))
        {
            $this->_views[$view->ViewName] = $view;
        }
        
    }
}
