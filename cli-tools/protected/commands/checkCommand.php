<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of checkCommand
 *
 * @author Warun Kietduriyakul <warun@jomyut.net>
 */
class checkCommand extends CConsoleCommand {
    protected static $httplog = null;
    public function init(){
        parent::init();
        if (self::$httplog == null){
            self::$httplog = new HTTPLogger();
        }
    }
    public function run($args){
        print_r($args);
        self::$httplog;
        $c = new CrawlerService();
        $c->search($args[0]);
    }
}

?>
