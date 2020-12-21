<?php

namespace Library\Models;

use \Library\Entities\Categories;

abstract class CategoriesManager extends \Library\Manager
{
    abstract protected function add(Categories $Categories);

    abstract protected function modify(Categories $Categories);

    abstract public function delete($id);

    abstract public function get($id);

    abstract public function getListeOf();

    public function save(Categories $Categories)
    {
        if ($Categories->isValid()) {
            $Categories->isNew() ? $this->add($Categories) : $this->modify($Categories);
        } else {
            throw new \RuntimeException('Le Articleaire doit être valide pour être enregistrer');
        }
    }
}
