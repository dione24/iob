<?php

namespace Library\Models;

use \Library\Entities\Categories;

class CategoriesManagerPDO extends CategoriesManager
{

    protected function add(Categories $Categories)
    {
        $requete = $this->dao->prepare("INSERT INTO categories(name,groupe) VALUES(:name,:groupe)");
        $requete->bindValue(':name', $Categories->name(), \PDO::PARAM_STR);
        $requete->bindValue(':groupe', $Categories->groupe(), \PDO::PARAM_STR);
        $requete->execute();
        $Categories->setId($this->dao->lastInsertId());
    }
    protected function modify(Categories $Categories)
    {
        $requete = $this->dao->prepare("UPDATE categories SET name=:name,groupe=:groupe  WHERE id=:id");
        $requete->bindValue(':id', $Categories->id(), \PDO::PARAM_INT);
        $requete->bindValue(':name', $Categories->name(), \PDO::PARAM_STR);
        $requete->bindValue(':groupe', $Categories->groupe(), \PDO::PARAM_STR);
        $requete->execute();
    }
    public function getListeOf()
    {
        $requete = $this->dao->prepare("SELECT * FROM categories ");
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Categories');
        $Categories = $requete->fetchAll();
        return $Categories;
    }
    public function get($id)
    {
        $requete = $this->dao->prepare('SELECT * FROM categories WHERE id=:id');
        $requete->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Categories');
        return $requete->fetch();
    }
    public function delete($id)
    {
        $requete = $this->dao->prepare("DELETE FROM categories WHERE id=:id");
        $requete->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $requete->execute();
    }
}
