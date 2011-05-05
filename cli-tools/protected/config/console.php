<?php

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Crawler Console',
        'import' => array('application.components.*'),
        'preload'=>array('log'),
        'components'=>array(
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					//'levels'=>'error, warning',
                                        //'logPath'=>date('YmdHis'),
				),
			),
		),
	),
);


