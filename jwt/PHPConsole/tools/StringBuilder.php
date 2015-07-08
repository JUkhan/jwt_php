<?php

/**
 * StringBuilder short summary.
 *
 * StringBuilder description.
 *
 * @version 1.0
 * @author Jasim.Uddin
 */
class StringBuilder
{
    var $_string = '';	
	
	public function append($string) {
		$this->_string .= $string;
        return $this;
	}
    public function appendFormat($stringFormat, ...$args) {       
		$this->_string .=call_user_func_array('sprintf', func_get_args());
        return $this;
	}
	public function appendLine(){
        $this->_string .= "\r\n";
        return $this;
    }
    public function appendTab(){
        $this->_string .= "\t";
        return $this;
    }
    public function appendTab2(){
        $this->_string .= "\t\t";
        return $this;
    }
    public function appendTab3(){
        $this->_string .= "\t\t\t";
        return $this;
    }
	public function toString() {
		return $this->_string;
	}
}
