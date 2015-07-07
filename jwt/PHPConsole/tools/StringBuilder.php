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
	}
	public function appendLine(){
        $this->_string .= '\r\n';
    }
    public function appendTab(){
        $this->_string .= '\t';
    }
	public function toString() {
		return $this->_string;
	}
}
