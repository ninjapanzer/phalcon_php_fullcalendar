<?php

use App\Forms\LoginForm;

class SessionController extends BaseController
{

    public function beforeExecuteRoute() {}

    public function indexAction()
    {
        $this->view->form = (new LoginForm());
    }

    /**
     * Starts a session in the admin backend
     */
    public function loginAction()
    {
        if ($this->request->isPost()) {
            $form = new LoginForm();
            $loggedIn = false;

            if ($form->isValid($_POST)) {
                $credentials = [
                    'email'    => $this->request->getPost('email', Phalcon\Filter::FILTER_EMAIL, ''),
                    'password' => $this->request->getPost('password', Phalcon\Filter::FILTER_STRIPTAGS, '')
                ];
                $loggedIn = $this->auth->check($credentials);
                if ($loggedIn) {
                    $this->flashSession->success('Welcome User');
                } else {
                    $this->flashSession->error('Invalid credentials');
                }
            }

            return $this->response->redirect('/');
        }
    }

    /**
     * Closes the session
     */
    public function logoutAction()
    {
        $this->auth->remove();

        return $this->response->redirect('index');
    }
}
