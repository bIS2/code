<?php

return array(

    'default' => 'pgsql',

    'connections' => array(
		'pgsql' => array(
			'driver'   => 'pgsql',
			'host'     => '127.0.0.1',
			'database' => 'bistest',
			'username' => 'bispgadmin',
			'password' => '%^$-*/-bIS-2014*-%',
			'charset'  => 'utf8',
			'prefix'   => '',
            'schema'   => 'public',
		),
    )
);