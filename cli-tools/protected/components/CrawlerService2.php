<?php

/**
 * Crawler Adapter for All Web/CLI Interface
 *
 * @author Warun Kietduriyakul <warun@jomyut.net>
 */
class CrawlerService2 extends CComponent{
    public function search($url){
        $init = new WebResource(null,0);
        $init->BaseURL = $url; 
        $init->ResourceURL = $url; 
        $init->checkResource();
    }
}

?>
