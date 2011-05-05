<?php

class URI extends CComponent{
    protected $_uri;
    private $__readyheader = false;
    protected $_header;
    protected $_returncode = null;

    public function getHeader($force = false){
        if ($force || !$this->__readyheader){
                //$this->_header = get_headers($this->_uri,1);
                // create a new cURL resource
                $ch = curl_init();

                // set URL and other appropriate options
                curl_setopt($ch, CURLOPT_URL, $this->_uri);
                curl_setopt($ch, CURLOPT_HEADER, 1);
                curl_setopt($ch,CURLOPT_NOBODY,1);
                curl_setopt ( $ch , CURLOPT_RETURNTRANSFER , 1 );

                // grab URL and pass it to the browser
                $header = curl_exec($ch);
                $headers = false;
                // close cURL resource, and free up system resources
                curl_close($ch);
                if ($header !== false){
                    
                    $headers = explode("\n",$header);
                    foreach($headers as &$head){
                        $part = explode(": ",$head);
                        
                        if (isset($part[1])){
                            $headers[$part[0]] = $part[1];
                        }
                    }
                }
                $this->_header = $headers;
            
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
    
    
    
    /** Properties **/    
    public function getURI(){ return $this->_uri;}
    public function setURI($value){
        // May check the patterns
        $this->_uri = $value;
    }
}

?>