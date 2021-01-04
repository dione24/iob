<?php

namespace Library\Models;

use \Library\Entities\User;

abstract class UserManager extends \Library\Manager
{
    abstract public function login($Login, $Password);
    abstract  protected function MyProfile();
    abstract protected function UpdateInfo();
    abstract protected function CheckPassword();
    abstract protected function ValidPassword();
    abstract protected function ListeUsers();
    abstract protected function ListeCaisse();
    abstract protected function VerifCaisse($Caisse, $Users);
    abstract protected function ListeStatut();
    abstract protected function AddUser();
    abstract protected function SendUserinfo($to, $login, $Password);
    abstract protected function DeleteUsers($Users);
    abstract protected function GetUserInfo($Users);
    abstract protected function UpdateUsers();
    abstract protected function AddChmod();
}