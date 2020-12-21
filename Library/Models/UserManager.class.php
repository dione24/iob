<?php

namespace Library\Models;

use \Library\Entities\User;

abstract class UserManager extends \Library\Manager
{
    abstract protected function add(User $User);

    abstract protected function modify(User $User);

    abstract public function login($Login, $Password);

    abstract public function delete($id);

    abstract public function get($id);

    abstract public function getListeOf();

    public function save(User $User)
    {
        if ($User->isValid()) {
            $User->isNew() ? $this->add($User) : $this->modify($User);
        } else {
            throw new \RuntimeException('Le Articleaire doit être valide pour être enregistrer');
        }
    }
}
