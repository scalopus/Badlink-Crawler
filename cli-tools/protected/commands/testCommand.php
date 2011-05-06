<?php

/**
 * Test Command
 *
 * @author Warun Kietduriyakul <warun@jomyut.net>
 */
class testCommand extends CConsoleCommand {
    /**
     *
     * @var WebResource Web Resource Handler 
     */
    private $webresource;
    public function run($args){
        if (count($args) < 2) {
            echo $this->getHelp();
            return;
        }
        $url = array_pop($args);
        $this->webresource = new WebResource();
        $this->webresource->BaseURL = $url;
        $this->webresource->ResourceURL = $url;
        foreach ($args as $f){
            $this->{"get$f"}();
        }
    }
    
    /**
     * Get HTTP Response Header in the Web Resource URL
     */
    public function getHeader(){
        print_r($this->webresource->getHeader());
    }
    /**
     * Get HTTP Return Code of the Web Resource URL
     */
    public function getXXX(){
        echo $this->webresource->getReturnCode();
    }
    /**
     * Get Link URL that appeared in the Web Resource URL
     */
    public function getLink(){
        $link =$this->webresource->searchLinkInContext();
        foreach ($link as $l){
            echo $l->ResourceURL . "\n";
        }
    }
    /**
     * Help Manual
     * @return String Help Manual 
     */
    public function getHelp(){
        $help = parent::getHelp();
        $help .= <<< EOC
 <function> <URL>
URL - URL that would like to get tested.

function :
header          Retrieve Header
link            Search for link in URL
xxx             HTTP return code

EOC;
        return $help;
    }
}

?>
