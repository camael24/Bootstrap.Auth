<?php
namespace {
}
namespace Application\Controller {

    abstract class Generic extends \Hoathis\Kit\Aggregator {

        public function construct() {
            $flash = new \Hoa\Session\Flash('popup');

            if (isset($flash['message'])) {

                switch ($flash['type']) {
                    case 'info':
                        $this->data->type = "alert alert-info";
                        break;
                    case 'success':
                        $this->data->type = "alert alert-success";
                        break;
                    case 'error':
                    default:
                        $this->data->type = "alert alert-error";


                }
                $this->data->message = $flash['message'];
                $this->view->addOverlay('hoa://Application/View/Flash.xyl');

            }


            $user = new \Hoa\Session\Session('user');


            if ($user->isEmpty() === true)
                $this->view->addUse('hoa://Application/View/Navbar/Default.xyl');
            else
                $this->view->addUse('hoa://Application/View/Navbar/Connect.xyl');

        }

        public function check($name, $strict = false, &$variable = null) {
            if ($variable === null)
                $variable = $_POST;

            if ($strict === true)
                if (array_key_exists($name, $variable) && !empty($variable[$name]))
                    return $variable[$name];
                else
                    return null;
            else
                if (array_key_exists($name, $variable))
                    return $variable[$name];
                else
                    return null;

        }

        public function popup($type, $message) {
            $flash            = new \Hoa\Session\Flash('popup');
            $flash['type']    = $type;
            $flash['message'] = $message;
        }

        public function guestGuard() {
            $user = new \Hoa\Session\Session('user');
            if ($user->isEmpty() === true) {
                $this->popup('info', 'You don`t have the require credential');
                $this->getKit('Redirector')->redirect('home-caller', array('_able' => 'connect'));
            }
        }


    }
}

