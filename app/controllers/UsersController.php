<?php

use App\Forms\UserForm;

class UsersController extends BaseController
{
  public function showAction($id)
  {
    $id = $this->filter->sanitize($id, Phalcon\Filter::FILTER_INT);
    $me = Users::findFirst([
      'conditions' => 'id = :id:',
      'bind' => ['id' => $id]
    ]);

    $this->view->user = $me;
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
