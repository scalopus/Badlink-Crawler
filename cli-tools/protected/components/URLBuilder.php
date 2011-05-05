<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of URLBuilder
 *
 * @author Warun Kietduriyakul <warun@jomyut.net>
 */
class URLBuilder extends CComponent {
    private $_baseURL = "";
    protected $_unprocessedtext;
    public function getRawText(){
        return $this->_unprocessedtext;
    }
    public function setBaseURL($baseurl){
        // Match the URL Patterns
        
        
        
        // Check for last tail
        
    }
    public function mergeWithText($something){
        // Remove ./ ../ /
    }
    protected function cdup(){
        
    }
}

?>
