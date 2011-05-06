<?php

/**
 * Traversal Command
 * 
 * This is command line interface to the Crawler service.
 * 
 *
 * @author Warun Kietduriyakul <warun@jomyut.net>
 */

class traversalCommand extends CConsoleCommand {

    public function run($args){
        if (count($args) <= 0){
            echo $this->getHelp(); return;
        }
        $c = new CrawlerService2();
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
