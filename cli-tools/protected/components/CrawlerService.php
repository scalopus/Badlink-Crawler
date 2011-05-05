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

       while (!empty($queue)){
           
           $newlink = array_pop($queue);
           // Filtering
           if (stripos($newlink->URI,$this->baseurl->URI)=== false){
               Yii::log("[Skip] " . $url . " is out of scope.", CLogger::LEVEL_PROFILE, "HTTP.Verbose");
               continue;
           }
           if (!$newlink->getHeader()){
                Yii::log($newlink->URI, CLogger::LEVEL_PROFILE, "HTTP.CANNOTCONNECT"); 
               continue;
           }
           if (!$newlink->isOK()){
               Yii::log($newlink->URI, CLogger::LEVEL_PROFILE, "HTTP.".$newlink->ReturnCode); 
               continue;
           } else {
               Yii::log($newlink->URI, CLogger::LEVEL_PROFILE, "HTTP.200");
           }

           if ($newlink->isText()){
               // Search inside
               $content = file_get_contents($newlink->URI);
               $urls = $this->getURL($content);
               //$i++;
               //if ($i == 1){
                   foreach($urls[4] as $url){
                       // Check Protocols
                       if (preg_match("/(http|https)+:\/\//",$url)){
                           // HTTP / HTTPS Protocols - Can be use immediately 
                           $fullurl = $url;
                       } else if (preg_match("/^([\w]+:)/m",$url)){
                           //Other Protocols
                           Yii::log("[Skip] " . $url . " is unsupported protocol.", CLogger::LEVEL_PROFILE, "HTTP.nonHTTP");
                           continue;
                       }
                       // check Relative URL
                       
                       // Check Absolute URL
                       
                       
                       
                       if (strpos($url,'/') === 0){
                           $fullurl = $this->baseurl->URI . $url;
                       } else if (strpos($url,'://') === false){
                           $fullurl = $this->baseurl->URI . '/' . $url;
                           //$fullurl = $this->baseurl->URI  . $url;
                       }
                       if (in_array($fullurl, $this->completedlist)){
                           Yii::log("[Skip] " . $url . " already in the traversal list.", CLogger::LEVEL_PROFILE, "HTTP.Verbose");
                           continue;
                       } else {
                           
                           $newURI = new URI();
                           $newURI->URI = $fullurl;
                           Yii::log("Enqueue: " . $fullurl . "", CLogger::LEVEL_PROFILE, "HTTP.Verbose");
                           array_push($queue,$newURI);
                           array_push($this->completedlist,$fullurl);
                       }
                      }
               //} if i > 0
           }
           //ob_flush();
           
       }
       
       
   }
   
}
?>
