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
                Yii::log("cannot create HTTP log directory " . $this->_logPath,"warning","logger");
            }
        }
    	protected function formatLogMessage($message,$level,$category,$time)
	{
		//return @date('Y/m/d H:i:s',$time)." [$level] [$category] $message\n";
            return "$message\n";
	}
}

?>
