<?php
namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Vokuro\Models\Profiles;

class UserForm extends Form
{
  public function initialize($entity = null, $options = null)
  {
    $name = new Text('name', [
      'placeholder' => 'Name'
    ]);

    $name->addValidators([
      new PresenceOf([
        'message' => 'The name is required'
      ])
    ]);

    $this->add($name);

    $email = new Text('email', [
      'placeholder' => 'Email'
    ]);

    $email->addValidators([
      new PresenceOf([
        'message' => 'The e-mail is required'
      ]),
      new Email([
        'message' => 'The e-mail is not valid'
      ])
    ]);

    $this->add($email);

    $password = new Password('password');
    $password->addValidators([
      new PresenceOf([
        'message' => 'Password required'
      ])
    ]);

    $this->add($password);
  }
}
