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
   
   function getURL($content)
    {
    $urls = null;
    $pattern = "/((src|href|background|cite|longdesc|profile)=(\"|\'))(\S*?)(?=\"|\')/m";
    preg_match_all($pattern, $content,$urls);
    return $urls;
    }

   public function search($url){
       //TODO: INPUT Regular Expression
       $this->baseurl = new URI();
       $this->baseurl->URI = $url;
       
       $queue = new SplQueue();
       $queue->push($this->baseurl);
       
$i = 0; //TODO: Remove

       // Get Header
       while (!$queue->isEmpty()){
           
           $newlink = $queue ->pop();
           
           
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
               $i++;
               if ($i == 1){
                   foreach($urls[4] as $url){
                       if (strpos($url,'/') === 0){
                           $fullurl = $this->baseurl->URI . $url;
                       } else if (strpos($url,'://') === false){
                           $fullurl = $this->baseurl->URI . '/' . $url;
                       } else {
                           // Check protocol is supported.
                           if (preg_match("/(http|https)+:\/\//",$url)){
                            $fullurl = $url;
                           } else {
                               echo "[SKIPED] " . $url . " use unsupported protocol.\n";
                               continue;
                           }
                       }
                       $newURI = new URI();
                       $newURI->URI = $fullurl;
                       echo "Enqueue: " . $fullurl . "\n";
                       $queue->enqueue($newURI);
                       
                       }
               }
           }
           //ob_flush();
           
       }
       
       
   }
   
}
?>
