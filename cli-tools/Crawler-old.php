<?php
/**
 * Simple Crawler (Depth-first-search)
 * Complete the process with the high-memory.
 * @author Warun Kietduriyakul <warun@jomyut.net>
 * 
 */
class Reference {
    public $uri;
    public $position;
}
class Resource {
    const HTTP_OK = 200;
    const HTTP_REDIRECT = 300;
    
    public $uri;
    public $status;
    public $reference;
    
}
class ResourceSet extends ArrayObject {
    private $resourcetable = null;
    public function has($value){
        foreach ($resourcetable as $resource){
            if ($resource->uri == $value) return true;
        }
        return false;
    }
}
class Crawler {
   private $queue = null ;
   private $lists = array();
   private $maxmemory = '1G';
   private $baseurl = '';
   private $logdirectory = null;
   public function __construct(){
       $this->logdirectory = date('YmdHis');
   }
   public function init(){
       // Increase the memory size
       ini_set('memory_limit',$this->maxmemory);
       // Deactivate script termination
       set_time_limit(0);
   }
   protected function recursiveSearch($url){
       
   }
   function getURL($content)
    {
    $urls = null;
    //$pattern = '/((([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?)/';
    //$pattern = "/(http:\/\/[\w\.\/?\r\n\-\d=]+)/m";
    //$pattern = "/((([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?)/m";
    //$pattern = "/((src|href|background|cite|longdesc|profile)=(\"|\'))(http:\/\/[\w\.\/?\r\n\-\d=]+)(\"|\')/m";
    //$pattern = "/((src|href|background|cite|longdesc|profile)=(\"|\'))((http:\/\/)*(.)+)(?=[\"\'])/m";
    $pattern = "/((src|href|background|cite|longdesc|profile)=(\"|\'))(\S*?)(?=\"|\')/m";
    
    preg_match_all($pattern, $content,$urls);
    return $urls;
    }

   public function search($url){
       //TODO: INPUT Regular Expression
       $this->init();
       $this->baseurl = $url;
       $queue = new SplQueue();
       $queue->push($this->baseurl);
$i = 0; //TODO: Remove
       echo "Create directory : " . $this->logdirectory . "\n";
       mkdir($this->logdirectory);
       // Get Header
       while (!$queue->isEmpty()){
           
           $url = $queue ->pop();
           //var_dump($url,$this->baseurl);
           
           // Filtering
           if (stripos($url,$this->baseurl)=== false){
               echo "[SKIPED] " . $url . " is out of scope.\n";
               continue;
               
           }
           $header = get_headers($url,1);
           
           if (!$header){
               // Logging to Failed log
                echo "[FAILED] " . $url ."\n";
               file_put_contents($this->logdirectory . "/failed.txt",$url . "\n",FILE_APPEND);
               continue;
           }
           if (preg_match("/200 OK/",$header[0])){
               echo "[200] " . $url ."\n";
               file_put_contents($this->logdirectory . "/200.txt",$url . "\n",FILE_APPEND);
           } else {
               echo "[XXX] $header[0] " . $url ."\n";
               file_put_contents($this->logdirectory . "/other.txt",$url . "\n",FILE_APPEND);
               continue;
           }
           //var_dump($header);
           if (preg_match("/text\/html/",$header['Content-Type'])){
               // Search inside
               $content = file_get_contents($url);
               //echo htmlentities($content);
               $urls = $this->getURL($content);
               //print_r($urls[4]);
               $i++;
               if ($i == 1){
                   foreach($urls[4] as $url){
                       if (strpos($url,'/') === 0){
                           $fullurl = $this->baseurl . $url;
                       } else if (strpos($url,'://') === false){
                           $fullurl = $this->baseurl . '/' . $url;
                       } else {
                           // Check protocol is supported.
                           if (preg_match("/(http|https)+:\/\//",$url)){
                            $fullurl = $url;
                           } else {
                               echo "[SKIPED] " . $url . " use unsupported protocol.\n";
                               continue;
                           }
                       }
                       
                       echo "Enqueue: " . $fullurl . "\n";
                       $queue->enqueue($fullurl);
                       
                       }
               }
           }
           //ob_flush();
           
       }
       
       
   }
   
}
$t = new Crawler();
$t->search("http://dev.w3.org/2008/link-testsuite/");

?>
