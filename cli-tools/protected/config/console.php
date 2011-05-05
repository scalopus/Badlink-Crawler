<?php

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Crawler Console',
        'import' => array('application.components.*',"application.components.log.*",'ext.FlushableLogRouter'),
        'preload'=>array('log'),
        'components'=>array(
		'log'=>array(
			'class'=>'FlushableLogRouter',
                        'autoFlush' => 1,
			'routes'=>array(
				array(
                                    'class'=>'CFileLogRoute',
                                    //'categories'=>'*',
                                    //'levels'=>'error, warning',
                                    //'logPath'=>date('YmdHis'),
				),
                                array(
                                    'class'=>'HTTPLogRoute',
                                    'categories'=>'HTTP.*',
                                ),
                                array(
                                    'class'=>'STDOUTLogRoute',
                                    'categories'=>'HTTP.*',
                                ),
                            ),
		),
	),
);


