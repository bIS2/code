<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');

$app->auth = function(){
	require 'opauth.conf.php';
	$Opauth = new Opauth( $config['opauth'] );
};


$app->group('/auth',function() use ($app) {

    $app->get('/github|openid|twitter|facebook|google', function () use ($app) { 
        $app->auth;
    });

    $app->post('/openid', function () use ($app) {
        $app->auth;
    });

    // CALLBACKS de las estrategias que lo necesitan
    $app->get('/(github|twitter)/(oauth2callback|oauth_callback)', function () use ($app) {
        $app->auth;
    });

    // CALLBACK de la authenticacion
    $app->get('/callback', function () {
        session_start();
        $response = $_SESSION['opauth'];
        //$response = unserialize(base64_decode( $_POST['opauth'] ));
        //$response = unserialize(base64_decode( $_GET['opauth'] ));
        echo var_dump($response);
    });

});

$app->notFound(function () use ($app) {
    echo json_encode(['error'=>'404']);
});

$app->run();