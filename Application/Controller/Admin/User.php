<?php
namespace {
}
namespace Application\Controller\Admin {

    class User extends Generic {

        public function ListAction() {

            $user             = new \Application\Model\User();
            $this->data->user = $user->all();
            $this->view->addOverlay('hoa://Application/View/Admin/User/List.xyl');
            $this->view->render();

        }

        public function EditAction($id) {

            $user = new \Application\Model\User();

            $error = false;
            if (!empty($_POST)) {
                $login     = $this->check('user');
                $password  = $this->check('pass');
                $rpassword = $this->check('rpass');
                $mail      = $this->check('mail');
                $rang      = $this->check('rang');


                if ($password !== $rpassword) {
                    $this->popup('error', 'The field password and retype your password must be egal ');
                    $error = true;
                }


                if ($error === true) {
                    $this->getKit('Redirector')->redirect('admin-user-id', array('id' => $id, '_able' => 'edit'));
                } else {
                    $user->setPassword($id, $password);
                    $user->setMail($id, $mail);
                    $user->setRang($id, $rang);
                    $user->setUsername($id, $login);


                    $this->popup('success', 'Edition complete');

                    $this->getKit('Redirector')->redirect('admin-user', array('_able' => 'list'));
                }
            }
            $data = $user->getById($id);
            $data = $data[0];
            foreach ($data as $key => $v) {

                if ($key === 'rang') {
                    $rang = new \Application\Model\Rang();
                    $v    = $this->buildSelect($rang->all(), $v);
                }

                $this->data->$key = $v;
            }

            $this->view->addOverlay('hoa://Application/View/Admin/User/Edit.xyl');
            $this->view->render();


        }

        private function buildSelect($rangList, $id) {
            $buffer = '<select name="rang">';
            foreach ($rangList as $elmt)
                $buffer .= '<option value="' . $elmt['idRang'] . '" ' . (($elmt['idRang'] === $id)
                    ? 'selected="selected"'
                    : '') . '>' . $elmt['RangLabel'] . '</option>';

            return $buffer . '</select>';


        }

    }
}

