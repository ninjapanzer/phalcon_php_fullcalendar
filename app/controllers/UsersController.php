<?php

use Phalcon\Mvc\Controller;
use App\Forms\UserForm;

class UsersController extends Controller
{

  public function showAction($id)
  {
    $this->view->user = Users::findById($id);
  }

  public function newAction()
  {
    $this->view->form = (new UserForm());
  }

  public function createAction()
  {
    $form = new UserForm(null);
    if ($form->isValid($_POST) == false) {
      foreach ($form->getMessages() as $message) {
        $this->flashSession->error($message->getMessage());
      }
      $this->view->form = $form;
      return $this->view->pick("users/new");
    } else {
      $user = new Users([
        "name" => $this->request->getPost('name', 'striptags'),
        "email" => $this->request->getPost('email', ['email', 'striptags'])
      ]);
      $user->setPassword($this->request->getPost('password', 'striptags'));
      $user->save();
      $this->flashSession->success("SUCCESS!!! :) :) !!!!");
      $this->response->redirect('users/show/'.$user->id);
    }
  }

  public function editAction()
  {
    // $user = User::findById($this->params('id'));
    // $this->view->form = (new UserForm($user));
  }

  public function updateAction()
  {

  }

}
