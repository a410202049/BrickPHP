<?php 
return [
    'twig_admin' => array(
		'extension'=> '.twig',
		'template_dir'=> "./app/View/admin/",
		'cache_dir'=> "./cache/admin",
		'debug'=>true,
		'auto_reload'=>true
	),
	'twig_home' =>array(
		'extension'=> '.twig',
		'template_dir'=> "./app/View/home/",
		'cache_dir'=> "./cache/home",
		'debug'=>true,
		'auto_reload'=>true
	)
];
