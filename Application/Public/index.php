<?php
require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'Core.link.php';

date_default_timezone_set('Europe/Paris');
header('Content-type: text/html; charset=utf-8');

from('Hoa')
    ->import('Database.Dal')
    ->import('Dispatcher.Basic')
    ->import('Dispatcher.Kit')
    ->import('Router.Http')
    ->import('Session.~')
    ->import('Session.Flash')
    ->import('Xyl.~')
    ->import('Xyl.Interpreter.Html.~')
    ->import('File.Read')
    ->import('File.ReadWrite')
    ->import('Http.Response');

from('Application')
    ->import('Model.*')
    ->import('Controller.Generic');


from('Hoathis')
    ->import('Context.~')
    ->import('Kit.Aggregator');


try {
    $dispatcher = new \Hoa\Dispatcher\Basic();
    $router     = new \Hoa\Router\Http();

    $dispatcher->setKitName('Hoathis\Kit\Aggregator');

    $context = new \Hoathis\Context\Context();
    $context->load();

    \Hoa\Database\Dal::initializeParameters(array(
        'connection.list.default.dal'      => 'Pdo',
        'connection.list.default.dsn'      => 'mysql:host=127.0.0.1;dbname=hoathis',
        'connection.list.default.username' => $context->mysql_user,
        'connection.list.default.password' => $context->mysql_pass,
        'connection.autoload'              => 'default'
    ));


    $router
        ->get('home', '/', 'main', 'index');


    $view = new \Hoa\Xyl\Xyl(
        new Hoa\File\Read('hoa://Application/View/Main.xyl'),
        new Hoa\Http\Response\Response(),
        new Hoa\Xyl\Interpreter\Html\Html(),
        $router
    );


    $dispatcher->dispatch(
        $router,
        $view

    );
}
catch (\Hoa\Core\Exception\Exception $e) {
    var_dump($e->getFormattedMessage());
    if ($e instanceof \Hoa\Session\Exception\Expired or $e instanceof \Hoa\Session\Exception\Locked or $e instanceof \Hoa\Session\Exception\Exception) {

        if (array_key_exists('QUERY_STRING', $_SERVER))
            $hash = urlencode($_SERVER['QUERY_STRING']);


        $session            = new \Hoa\Session\Flash('popup');
        $session['type']    = 'info';
        $session['message'] = 'You have been disconnect, because you don`t use your session since long time ...';

        header('Location:/connect.html?redirect=' . $hash);
        exit();
    } else {

        $complement = '';
        if (array_key_exists('QUERY_STRING', $_SERVER))
            $complement = $_SERVER['QUERY_STRING'];

        $read = new \Hoa\File\ReadWrite('hoa://Data/Variable/Log/Exception.log');
        $read->writeAll('[' . date('d/m/Y H:i:s') . '] ' . $complement . ' ' . str_replace(array("\n", "\t", "\r"), '', $e->raise(true)) . "\n");

        $session            = new \Hoa\Session\Flash('popup');
        $session['type']    = 'error';
        $session['message'] = 'An error has open, an report has been sent to the administrator thanks for your help';

        header('Location:/');
        exit();
    }

}



