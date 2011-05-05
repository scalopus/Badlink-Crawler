<?php


class ReferenceURI extends URI {
    protected $_location;
    protected $_line;
    protected $_noofchar;
    public function setLocation($line =0,$char =0){
        $this->_line = $line;
        $this->_char = $char;
    }
    public function getLine(){return $this->_line;}
    public function getChar(){return $this->_char;}
}





/**
 * Description of DataStructure
 *
 * @author Warun Kietduriyakul <warun@jomyut.net>
 */
class Connection extends CComponent{
    protected $_uri;
    protected $_handler;
    protected $_returncode;
    protected $_status;
    
}

?>
