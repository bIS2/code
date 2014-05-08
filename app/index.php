<?php
require 'vendor/autoload.php';

use RedBean_Facade as R;
R::setup('pgsql:host=localhost;dbname=bis','postgres','admin'); //postgresql
define( 'REDBEAN_MODEL_PREFIX', '\\models\\' ); 

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
        echo json_encode($response);
    });

});

$app->group('/groups',function() use ($app) {
    $limit = 10;

    $app->get('/(page/:page)', function($page=0) use ($limit){
        $count = R::count('holgroups');
        $offset = $page * $limit;
        $groups = R::findAll('holgroups',"limit 10 offset $offset");
        echo json_encode( R::exportAll($groups) );
    });

    $app->get('/:id', function($id){
        $condition = (strlen($id) > 10) ? 'sys1=?' : 'id=?';
        $group = R::findOne('holgroups',$condition, [$id]);
        echo $group;
    });

});

$app->get('/hol/:group_id', function($id){
    $hols = R::find('holbis','holgroups_id=?', [$id]);
    echo json_encode( R::exportAll($hols) );
});



$app->notFound(function () use ($app) {
    echo json_encode(['error'=>'404']);
});

$app->run();