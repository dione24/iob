<?php

namespace Library\Entities;

class Categories extends \Library\Entity
{
    protected $name;
    protected $groupe;

    public function isValid()
    {
        return !(empty($this->name) || empty($this->groupe));
    }
    public function name()
    {
        return $this->name;
    }
    public function groupe()
    {
        return $this->groupe;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;
    }
}
