<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
  public $id;
  public $name;
  public $email;
  public $password;

  public function setPassword($newPassword)
  {
    $securityContext = $this->getDI()->getSecurity();
    $hashedNewPassword = $securityContext->hash($newPassword);
    $this->password = $hashedNewPassword;
  }
}
