<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CrawlerService2
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
