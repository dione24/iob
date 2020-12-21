<?php
	namespace Library\Entities;

	class Article extends \Library\Entity {
		protected $Title;
		protected $Content;
		protected $Date;

		public function isValid() {
			return !(empty($this->Title) || empty($this->Content));
		}

    public function Title(){
      return $this->Title;
    }
    public function Content(){
      return $this->Content;
    }
    public function Date(){
      return $this->Date;
    }

    public function setTitle($Title){
      $this->Title=$Title;
    }
    public function setContent($Content){
      $this->Content=$Content;
    }
    public function setDate($Date){
      $this->Date=$Date;
    }


	}
