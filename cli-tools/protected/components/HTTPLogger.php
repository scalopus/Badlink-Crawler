<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommandLogger
 *
 * @author Warun Kietduriyakul <warun@jomyut.net>
 */
class HTTPLogger extends CFileLogRoute{
    protected $_logPath;
        public function __construct(){
            
            $this->_logPath = '../logs/' . date('YmdHis');
            if (mkdir($this->_logPath,0777,true)){
                Yii::log("create HTTP log directory " . $this->_logPath,"info","logger");
            } else {
                if (is_dir($this->_logPath)){
                    Yii::log("HTTP log directory is already exists. " . realpath($this->_logPath) ,"info","logger");
                } else {
                    Yii::log("HTTP log directory cannot be created. " . realpath($this->_logPath),"error","logger");
                }
            }
        }
    	protected function formatLogMessage($message,$level,$category,$time)
	{
		//return @date('Y/m/d H:i:s',$time)." [$level] [$category] $message\n";
            return "$message\n";
	}
        public function log($code,$message){
            file_put_contents($this->_logPath . "/" . $code . ".log",$message . "\n",FILE_APPEND);
        }
        public function getLogPath(){
            return $this->_logPath;
        }
}

?>
