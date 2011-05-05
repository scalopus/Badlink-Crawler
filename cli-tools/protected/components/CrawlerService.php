<?php
/**
 * Simple Crawler (Depth-first-search)
 * Complete the process with the high-memory.
 * @author Warun Kietduriyakul <warun@jomyut.net>
 * 
 */
Yii::import('application.components.URI');


class CrawlerService extends CComponent{
   private $baseurl = '';
   private $completedlist = array();
   function getURL($content)
    {
    $urls = null;
    $pattern = "/((src|href|background|cite|longdesc|profile)=(\"|\'))(\S*?)(?=\"|\'|#)/m";
    preg_match_all($pattern, $content,$urls);
    return $urls;
    }

   public function search($url){
       //TODO: INPUT Regular Expression
       $this->baseurl = new URI();
       $this->baseurl->URI = $url;
       
       $this->completedlist += array($url);
       $queue = array($this->baseurl);
//$i = 0; //TODO: Remove

       // Get Header
       while (!empty($queue)){
           
           $newlink = array_pop($queue);
           // Filtering
           if (stripos($newlink->URI,$this->baseurl->URI)=== false){
               //Yii::log("[SKIPED] " . $newlink->URI . " is out of scope.\n","info","HTTP");
               continue;
               
           }
           
           
           if (!$newlink->getHeader()){
               // Logging to Failed log
                echo "[FAILED] " . $newlink->URI ."\n";
                //Yii::log('Failed',$newlink->URI,"info","HTTP");
                Yii::log($newlink->URI, CLogger::LEVEL_PROFILE, "HTTP.CANNOTCONNECT"); 
               continue;
           }
           if ($newlink->isOK()){
               echo "[200] " . $newlink->URI ."\n";
               //Yii::log('200',$newlink->URI,"info","HTTP");
               Yii::log($newlink->URI, CLogger::LEVEL_PROFILE, "HTTP.200");
           } else {
               echo "[" . $newlink->ReturnCode . "] " . $newlink->URI ."\n";
               Yii::log($newlink->URI, CLogger::LEVEL_PROFILE, "HTTP.".$newlink->ReturnCode); 
               continue;
           }
           //var_dump($header);
           if ($newlink->isText()){
               // Search inside
               $content = file_get_contents($newlink->URI);
               $urls = $this->getURL($content);
               //$i++;
               //if ($i == 1){
                   foreach($urls[4] as $url){
                       // Check Protocols
                       
                       // check Relative URL
                       
                       // Check Absolute URL
                       
                       
                       
                       if (strpos($url,'/') === 0){
                           $fullurl = $this->baseurl->URI . $url;
                       } else if (strpos($url,'://') === false){
                           $fullurl = $this->baseurl->URI . '/' . $url;
                           //$fullurl = $this->baseurl->URI  . $url;
                       } else {
                           // Check protocol is supported.
                           if (preg_match("/(http|https)+:\/\//",$url)){
                            $fullurl = $url;
                           } else {
                               echo "[SKIPED] " . $url . " use unsupported protocol.\n";
                               continue;
                           }
                       }
                       if (in_array($fullurl, $this->completedlist)){
                           echo "[SKIPED] " . $url . " is already in the list.\n";
                           continue;
                       } else {
                           
                           $newURI = new URI();
                           $newURI->URI = $fullurl;
                           echo "Enqueue: " . $fullurl . "\n";
                           //$queue->enqueue($newURI);
                           array_push($queue,$newURI);
                           array_push($this->completedlist,$fullurl);
                           //var_dump($this->completedlist);
                       }
                       }
               //} if i > 0
           }
           //ob_flush();
           
       }
       
       
   }
   
}
?>
