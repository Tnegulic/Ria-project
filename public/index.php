<?php
	use Phalcon\Loader;
	use Phalcon\DI\FactoryDefault;
	use Phalcon\Mvc\View;
	use Phalcon\Mvc\Url as UrlProvider;
	use Phalcon\Mvc\Application;
	use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
	use Phalcon\Session\Adapter\Files as Session;
	use Phalcon\Flash\Direct as FlashDirect;
	use Phalcon\Flash\Session as FlashSession;


	define('BASE_PATH',dirname(__DIR__));
	define('APP_PATH',BASE_PATH . '/app');

	$loader = new Loader();
	$loader->registerDirs(
		[
			APP_PATH . '/controllers/',
			APP_PATH . '/models/',
		]
	);

	$loader->register();

	$di = new FactoryDefault();

	$di->set('view', function (){
		$view = new View();
		$view->setViewsDir(APP_PATH . '/views/');
		return $view;
	});

	$di->set('url',function(){
		$url = new UrlProvider();
		$url->setBaseUri('/');
		return $url;
	});

	$di->set('db',function () {
		return new DbAdapter([
			'host' => '127.0.0.1',
			'username' => 'root',
			'password' => 'root',
			'dbname' => 'RIA',
			'options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
		]);
	});

	$di->setShared("session",function (){
		$session = new Session();
		$session->start();
		return $session;
	});

	$di->set(
    	'flash',
    	function () {
        	return new FlashDirect();
    	}
	);

	$di->set(
    	'flashSession',
    	function () {
        	return new FlashSession();
    	}
	);


	$app = new Application($di);

	try {
		$response = $app->handle();
		$response->send();

	} catch (Exception $e) {
		echo "Exception: ".$e->getMessage();
	}


?>