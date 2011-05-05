<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Webpage
 *
 * @author Warun Kietduriyakul <warun@jomyut.net>
 */
class WebResource extends CComponent {
    private $content = null;
    private $link = array(); // Linked List to External Link
    private $backlink = null; // Linked List (Back link to this page)
    private $url = null;
    private $depth = false;
    private $protocol = 'http';
    private $__readyheader = false;
    protected $_header;
    protected $_returncode = null;
    public $supportedprotocols = array('http','https');
    
    public function getHeader($force = false){
        if ($force || !$this->__readyheader){
                //$this->_header = get_headers($this->_uri,1);
                // create a new cURL resource
                $ch = curl_init();

                // set URL and other appropriate options
                curl_setopt($ch, CURLOPT_URL, $this->url['resource']);
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
        if (!isset($this->header['Content-Type'])) {return false;}
        return (preg_match("/text\/html/",$this->header['Content-Type']))? true:false;
    }
    public function getReturnCode(){
        if (!$this->__readyheader){$this->getHeader();}
        return $this->_returncode;
    }
    /**
     *
     * @param String $url Initialize the Web Resource URL
     * @param WebResource $backlink the Reference to this Web resource
     */
    public function __construct($backlink =null,$depth=false){
        $this->backlink = $backlink; //WebResource Object.
        $this->depth = $depth;
    }
    public function getBaseURL(){
        return $this->url['basedir'];
    }
///    public function setBaseURL($url){
//        // Cut the string to be 3 Phase (Host : Path : QueryString)
//        //$patterns = "/(?<Host>(http|https):\/\/(.)+?)(?=\/)(?<Path>(.)+\/)(?<File>(.)+(?=\?))(?<QueryString>(.)+)/";
//        $patterns = "/(?<Host>(http|https):\/\/(.)+?)(?=\/)(?<Path>(.)+\/)(?<File>(.)+(?=\?))/";
//        if (preg_match($patterns,$url,$matches)){
//            $this->url['baseurl'] = $matches['Host'] . $matches['Path'] . $matches['File']; // Base URL to current directory include script name (if present)
//            $this->url['basedir'] = $matches['Host'] . $matches['Path'] ; // Base URL directory without script name
//            $this->url['rooturl'] = $matches['Host']; // Root URL
//            return true;
//        }
//        $patterns = "/(?<Host>(http|https):\/\/(.)+?)(?=\/)(?<Path>(.)+\/)/";
//        if (preg_match($patterns,$url,$matches)){
//            $this->url['baseurl'] = $matches['Host'] . $matches['Path'] ; // Base URL to current directory include script name (if present)
//            $this->url['basedir'] = $matches['Host'] . $matches['Path'] ; // Base URL directory without script name
//            $this->url['rooturl'] = $matches['Host']; // Root URL
//            return true;
//        } else {
//            $this->url['baseurl'] = $url;
//            $this->url['basedir'] = $url;
//            $this->url['rooturl']= $url;
//        }
        public function setBaseURL($url){
            $patterns = '/(?<Root>((http|https)(:\/\/))((.)+?(?=[\/\?]|$)))(?<Path>(.)+(?=[\/]))?(?<Filename>(.)+?(?=[#\?]|$))?/';
            if (preg_match($patterns,$url,$matches)){
                $path = isset($matches['Path'])?$matches['Path']:'';
                $filename = isset($matches['Filename'])?$matches['Filename']:'';
                $this->url['baseurl'] = $matches['Root'] . $path . $filename; // Base URL to current directory include script name (if present)
                $this->url['basedir'] = $matches['Root'] . $path ; // Base URL directory without script name
                $this->url['rooturl'] = $matches['Root'];           // Root URL
                return $this->url;
            }
        }
        
    public function getResourceURL(){
        return $this->url['resource'];
    }
    /**
     * This function use to merge the Priority URL to the BaseURL. 
     * It can be Relative/Absolute Path. If Protocol present in context
     * it will replace Resource URL. If it is relative path, it will append
     * to the Base URL
     * 
     * @param String $url Priority URL
     */
    public function setResourceURL($url){
        $this->url['rawtext'] = $url;
        // check Relative URL
        
       
        //$patterns = '/^((?<Protocol>[\w]+:)?\/\/)?((?<Username>[\d\w]|%[a-fA-f\d]{2,2})+(:(?<Password>[\d\w]|%[a-fA-f\d]{2,2})+)?@)?(?<Host>[\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(?<Port>:[\d]+)?(?<Directory>\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(?<QueryString>\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
        $patterns = '/^((?<Protocol>[\w]+):)/';
        $matches = null;
        preg_match($patterns,$url,$matches);
        if (isset($matches['Protocol'])){
            $this->url['resource'] = $url;
            $this->protocol = $matches['Protocol'];
            return $this->url['resource'];
        } 
        
        // Remove until it is the same
        do {
            $old = $url;
            $url = preg_replace('/((([-+_~.\d\w]|%[a-fA-f\d]{2,2})+)\/\.\.\/)/', '/', $url); // /ABC/../ -> /
            $change = (strcmp($old,$url)==0)? false:true;
        } while($change);
        
        // Remove first ../
//        do {
//            $old = $url;
//            $url = preg_replace('/(\/\.\.\/)+/', '/', $url);
//            $change = (strcmp($old,$url)==0)? false:true;
//        } while($change);
        $url = preg_replace('/(\/\.\.\/)+/', '/', $url);
        $url = preg_replace('/(\.\/)+/', '/', $url);

       // remove //
       $url = preg_replace('/([\/]{2,})/', '/', $url);
       // Remove Whitespace
       $url = preg_replace('/(\s)/', '', $url);

        if (strpos($url,'/') === 0){
            $this->url['resource'] = $this->url['rooturl'] . $url;
        } else {
            $this->url['resource'] = $this->url['basedir'] .'/'. $url;
        }

        return $this->url['resource'];
    }

    public function isInBaseURL(){
        return (strpos($this->url['resource'],$this->url['basedir']) !== false)? true:false;
    }
    public function isSupportedProtocol(){
        foreach ($this->supportedprotocols as $p){
            if ($this->protocol == $p) return true;
        }
        return false;
        
    }
    public function checkResource(){
        if (!$this->isSupportedProtocol()){
           Yii::log($this->url['resource'] . "\tRef: " . $this->backlink->ResourceURL, CLogger::LEVEL_PROFILE, "HTTP.Skip.Unsupported");
           return; 
        }

        // Check Traversal Set
        if (in_array($this->url['resource'],TraversalSet::$CompleteList)){
           Yii::log($this->url['resource'], CLogger::LEVEL_PROFILE, "HTTP.Skip.Duplicated");
           return;
        }
        // Add to Traversal Set
        array_push(TraversalSet::$CompleteList,$this->url['resource']);
        
        // Read Header
        if (!$this->getHeader()){
           Yii::log($this->url['resource']."\tRef: " . $this->backlink->ResourceURL, CLogger::LEVEL_PROFILE, "HTTP.Failed"); 
           return;
        }
        // Check Response Code
        if (!$this->isOK()){
           Yii::log($this->url['resource'] . "\tRef: " . $this->backlink->ResourceURL, CLogger::LEVEL_PROFILE, "HTTP.".$this->ReturnCode); 
           return;
        } else {
               Yii::log($this->url['resource'], CLogger::LEVEL_PROFILE, "HTTP.200");
        }
        // Check Content-type
        if (!$this->isText()){
            return;
        }
        // Check External Link
        if (!$this->isInBaseURL()){
            return;
        }
        // Read Content
        $this->loadContent();
        // Search any link in context.
        $this->searchLinkInContext();
        $this->goSearch();
    }
    protected function goSearch(){
        foreach ($this->link as $l){
            $l->checkResource();
        }
    }
    
    protected function fixBaseDir($directory){
   
    }
    protected function loadContent(){
        $this->content = file_get_contents($this->url['resource']);
    }
    protected function searchLinkInContext(){
        $urls = null;
        $pattern = "/((src|href|background|cite|longdesc|profile)=(\"|\'))(?<Link>\S*?)(?=\"|\'|#)/m";
        preg_match_all($pattern, $this->content,$urls);
        //var_dump($urls['Link']);exit();
        foreach ($urls['Link'] as $url){
            $newlink = new WebResource($this,($this->depth + 1));
            $newlink->BaseURL = $this->BaseURL;
            $newlink->ResourceURL = $url;
            array_push($this->link, $newlink);
        }
        return $this->link;
    }
  
}

?>
