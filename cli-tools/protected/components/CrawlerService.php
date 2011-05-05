<?php
/**
 * Simple Crawler (Depth-first-search)
 * Complete the process with the high-memory.
 * @author Warun Kietduriyakul <warun@jomyut.net>
 * 
 */

class CrawlerService extends CComponent{
   private $baseurl = '';
   protected $httplog = null;
   protected $servicelog = null;
   public function __construct(){
        if ($this->httplog == null){
            $this->httplog = new HTTPLogger();
        }
        $this->servicelog = new CFileLogRoute();
        $this->servicelog->setLogPath($this->httplog->logpath);
        $this->servicelog->init();
    }
   function getURL($content)
    {
    $urls = null;
    $pattern = "/((src|href|background|cite|longdesc|profile)=(\"|\'))(\S*?)(?=\"|\')/m";
    preg_match_all($pattern, $content,$urls);
    return $urls;
    }

   public function search($url){
       //TODO: INPUT Regular Expression
       $this->baseurl = $url;
       $queue = new SplQueue();
       $queue->push($this->baseurl);
       
$i = 0; //TODO: Remove

       // Get Header
       while (!$queue->isEmpty()){
           
           $url = $queue ->pop();
           
           // Filtering
           if (stripos($url,$this->baseurl)=== false){
               $this->servicelog->log("[SKIPED] " . $url . " is out of scope.\n","info");
               continue;
               
           }
           $header = get_headers($url,1);
           
           if (!$header){
               // Logging to Failed log
                echo "[FAILED] " . $url ."\n";
                $this->httplog->log('Failed',$url);
               continue;
           }
           if (preg_match("/200 OK/",$header[0])){
               echo "[200] " . $url ."\n";
               $this->httplog->log('200',$url);
           } else {
               echo "[XXX] $header[0] " . $url ."\n";
               $this->httplog->log('XXX',$url);
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
?>
