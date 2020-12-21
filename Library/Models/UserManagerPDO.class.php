<?php

namespace Library\Models;

use \Library\Entities\User;

class UserManagerPDO extends UserManager
{

    public function login($login, $Password)
    {
        $requete = $this->dao->prepare("SELECT *  FROM users WHERE login=:login AND password=:password");
        $requete->bindValue(':login', $login, \PDO::PARAM_STR);
        $requete->bindValue(':password', $Password, \PDO::PARAM_STR);
        $requete->execute();
        $resultat = $requete->fetch();
        return $resultat;
    }
    protected function add(User $User)
    {
        $requete = $this->dao->prepare("INSERT INTO users(login,password) VALUES(:login,:password)");
        $requete->bindValue(':login', $User->login(), \PDO::PARAM_STR);
        $requete->bindValue(':password', $User->password(), \PDO::PARAM_STR);
        $requete->execute();
        $User->setId($this->dao->lastInsertId());
    }
    protected function modify(User $User)
    {
        $requete = $this->dao->prepare("UPDATE users SET login=:login,password=:password  WHERE id=:id");
        $requete->bindValue(':id', $User->id(), \PDO::PARAM_INT);
        $requete->bindValue(':login', $User->login(), \PDO::PARAM_STR);
        $requete->bindValue(':password', $User->password(), \PDO::PARAM_STR);
        $requete->execute();
    }
    public function getListeOf()
    {
        $requete = $this->dao->prepare("SELECT * FROM users ");
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\User');
        $Users = $requete->fetchAll();
        return $Users;
    }
    public function get($id)
    {
        $requete = $this->dao->prepare('SELECT * FROM users WHERE id=:id');
        $requete->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\User');
        return $requete->fetch();
    }
    public function delete($id)
    {
        $requete = $this->dao->prepare("DELETE FROM users WHERE id=:id");
        $requete->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $requete->execute();
    }
}
