<?php

class URI extends CComponent{
    protected $_uri;
    private $__readyheader = false;
    protected $_header;
    protected $_returncode = null;
    public function getURI(){ return $this->_uri;}
    public function setURI($value){
        // May check the patterns
        $this->_uri = $value;
    }
    public function getHeader($force = false){
        if ($force || !$this->__readyheader){
                $this->_header = get_headers($this->_uri,1);
            
            if ($this->_header === false){
                // Cannot get the header;
            } else {
                $returncode =null;
                preg_match("/(\d{3})/",$this->_header[0],$returncode);
                $this->_returncode = $returncode[0];
            }
            
            $this->__readyheader = true;
        }
        return $this->_header;
    }
    public function isOK(){
        return (preg_match("/200 OK/",$this->header[0]))? true:false;
    }
    public function isText(){
        return (preg_match("/text\/html/",$this->header['Content-Type']))? true:false;
    }
    public function getReturnCode(){
        if (!$this->__readyheader){$this->getHeader();}
        return $this->_returncode;
    }
}

?>