<?php

use Phalcon\Mvc\Controller;

class BaseController extends Controller
{

  protected $loggedIn = false;
  protected $currentUser = null;

  public function initialize()
  {

  }

  public function beforeExecuteRoute()
  {
    $loggedIn = $this->auth->isLoggedIn();
    if (!$loggedIn) {
      $this->dispatcher->forward(['controller' => 'session', 'action' => 'index']);
    }
  }
}
