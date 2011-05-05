<?php


class STDOUTLogRoute extends CLogRoute{
        public function init(){
            parent::init();
        }
    	protected function formatLogMessage($message,$level,$category,$time)
	{
            return date("H:m:s") ." $category $message\n";
	}
        
        protected function processLogs($logs)
	{
            foreach ($logs as $log)
            echo $this->formatLogMessage($log[0],$log[1],$log[2],$log[3]);
	}
}

?>
