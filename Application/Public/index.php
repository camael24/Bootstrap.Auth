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
    ->import('Controller.Generic')
    ->import('Controller.Admin.*');


from('Hoathis')
    ->import('Kit.Aggregator');


try {
    $dispatcher = new \Hoa\Dispatcher\Basic();
    $router     = new \Hoa\Router\Http();

    $s = new \Hoa\Session\Session('user');

    $dispatcher->setKitName('Hoathis\Kit\Aggregator');

    \Hoa\Database\Dal::initializeParameters(array(
        'connection.list.default.dal'      => 'Pdo',
        'connection.list.default.dsn'      => 'mysql:host=127.0.0.1;dbname=hoathis',
        'connection.list.default.username' => 'camael',
        'connection.list.default.password' => 'toor', // DEV Mdp , F### :D
        'connection.autoload'              => 'default'
    ));


    /*
    * Controlleur, , Action , Variable
    * http://sample.hoathis.hoa/ => Project , List , $project = sample
    * http://sample.hoathis.hoa/edit.html => Project , Edit , $project = sample
    * http://sample.hoathis.hoa/delete.html => Project , Delete , $project = sample
    * http://hoathis.hoa/thehawk => User , Profil , $user = thehawk
    * http://hoathis.hoa/thehawk/edit.html => User , Edit , $user = thehawk
    * http://hoathis.hoa/thehawk/delete.html => User , Delete , $user = thehawk
    * http://hoathis.hoa/ => Main , Index
    * http://hoathis.hoa/search.html => Main , Search
    * http://hoathis.hoa/a/ => Admin , Index
    * http://hoathis.hoa/a/users.html => Admin , Users
    * http://hoathis.hoa/a/users/1 => Admin , Users , $user = thehawk
    */
// $router->setSubdomainSuffix('hoathis');

    $router
        ->get_post('admin-  user-id', '/a/user/(?<_able>[^-]+)-(?<id>[^\.]+)\.html', 'admin\user')
        ->get_post('admin-user', '/a/user/(?<_able>[^\.]+)\.html', 'admin\user')
        ->get_post('admin-project-id', '/a/project/(?<_able>[^-]+)-(?<id>[^\.]+)\.html', 'admin\project')
        ->get_post('admin-project', '/a/project/(?<_able>[^\.]+)\.html', 'admin\project')
        ->get_post('admin-home', '/a/', 'admin\main', 'index')

        ->get_post('project-caller', '/p/(?<project>[^/]+)/(?<_able>[^\.]+)\.html', 'project')
        ->get('project-home', '/p/(?<project>[^/]+)/', 'project', 'info')
        ->get_post('user-caller', '/(?<user>[^/]{3,})/(?<_able>[^\.]+)\.html', 'user', 'index')
        ->get('user-home', '/(?<user>[^/]{3,})/', 'user', 'profil')
        ->get_post('home-caller', '/(?<_able>[^\.]+)\.html', 'main')
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


