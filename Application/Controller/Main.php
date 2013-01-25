<?php
namespace {
}
namespace Application\Controller {

    class Main extends Generic {

        public function IndexAction() {

            $this->view->addOverlay('hoa://Application/View/Main/Index.xyl');
            $this->view->render();

        }

        public function RegisterAction() {
            if (!empty($_POST)) {
                $login     = $this->check('login', true);
                $password  = $this->check('pass', true);
                $rpassword = $this->check('rpass', true);
                $mail      = $this->check('mail', true);

                $error = false;
                if ($login === null) {
                    $this->popup('error', 'The field login is empty ');
                    $error = true;
                } else if ($password === null) {
                    $this->popup('error', 'The field password is empty ');
                    $error = true;
                } else if ($rpassword === null) {
                    $this->popup('error', 'The field retype-password is empty ');
                    $error = true;
                } else if ($mail === null) {
                    $this->popup('error', 'The field Email is empty ');
                    $error = true;
                } else if ($password !== $rpassword) {
                    $this->popup('error', 'The filed password and retype your password must be egal ');
                    $error = true;
                } else if (strlen($login) < 3) {
                    $this->popup('error', 'Your login must have over 3 characters !');
                    $error = true;
                }

                $userModel = new \Application\Model\User();


                if (!$userModel->checkMail($mail)) {
                    $this->popup('error', 'Your email address has ever register in our database ');
                    $error = true;
                }
                if (!$userModel->checkUser($login)) {
                    $this->popup('error', 'Your login name has ever register in our database');
                    $error = true;
                }
                if ($error === true) {
                    $this->getKit('Redirector')->redirect('home-caller', array('_able' => 'register'));
                } else {

                    $userModel->insert($login, $password, $mail);

                    $this->popup('success', 'Your register is an success !, welcome here you can connect');
                    $this->getKit('Redirector')->redirect('home-caller', array('_able' => 'connect'));
                }
            }


            $this->view->addOverlay('hoa://Application/View/Main/Register.xyl');
            $this->view->render();
        }

        public function ConnectAction() {

            $s = new \Hoa\Session\Session('user');
            if (!$s->isEmpty())
                $this->getKit('Redirector')->redirect('home', array());

            $query = $this->router->getQuery(); //TODO a faire
            $page  = (isset($query['redirect']) && !empty($query['redirect']))
                ? $query['redirect']
                : null;


            $this->data->redirect = $page;


            if (!empty($_POST)) {
                $email    = $this->check('login', true);
                $password = $this->check('password', true);
                $redirect = $this->check('redirect', true);


                $error = false;
                if ($email === null) {
                    $this->popup('error', 'The field login is empty ');
                    $error = true;
                } else if ($password === null) {
                    $this->popup('error', 'The field password is empty ');
                    $error = true;
                }


                $user = new \Application\Model\User();
                if (!$user->connect($email, $password)) {
                    $this->popup('error', 'This credentials are not reconized here, your are might be banned or unactived');
                    $error = true;
                }

                if ($error === true) {
                    $this->getKit('Redirector')->redirect('home-caller', array('_able' => 'connect'));
                } else {
                    $sUser             = new \Hoa\Session\Session('user');
                    $sUser['idUser']   = $user->idUser;
                    $sUser['username'] = $user->username;
                    $sUser['email']    = $user->mail;

                    $this->popup('success', 'Hello ' . $user->username);
                    if ($redirect === null)
                        $this->getKit('Redirector')->redirect('home', array());
                    else {
                        header('location:' . $page);
                        exit;
                    }
                }

            }

            $this->view->addOverlay('hoa://Application/View/Main/Connect.xyl');
            $this->view->render();
        }

        public function ForgotAction() {
            $this->popup('info', 'this function is not implement yet!'); //TODO change here
            $this->getKit('Redirector')->redirect('home', array());

        }

        public function DisconnectAction() {
            \Hoa\Session\Session::destroy();
            $this->getKit('Redirector')->redirect('home', array());
        }

    }
}

