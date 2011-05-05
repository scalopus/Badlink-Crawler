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

    public function run($args){
        print_r($args);
        $c = new CrawlerService();
        $c->search($args[0]);
    }
    public function getHelp(){
        $help = parent::getHelp();
        $help .= <<< EOC
 [parameter] BaseURI
BaseURI : Base URI is the entry point which crawler begin to traversal
Parameter Lists:
-v --verbose            Running in verbose mode
-m:<max_memory>         Max memory size        
-r --related-site       Traversal to the external website which is not in Base URI        
-n --no-follow          Traversal to only Base URI
-d:<depthlevel>         Number of Depth to traversal



EOC;
        return $help;
    }
}

?>
