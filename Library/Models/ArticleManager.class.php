<?php

namespace Library\Models;

use \Library\Entities\Article;

abstract class ArticleManager extends \Library\Manager
{
	abstract protected function add(Article $Article);

	abstract protected function modify(Article $Article);

	abstract public function delete($id);

	abstract public function get($id);

	abstract public function getListeOf();
	abstract public function search($Title);

	public function save(Article $Article)
	{
		if ($Article->isValid()) {
			$Article->isNew() ? $this->add($Article) : $this->modify($Article);
		} else {
			throw new \RuntimeException('Le Articleaire doit être valide pour être enregistrer');
		}
	}
}
