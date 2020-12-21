<?php

namespace Library\Models;

use \Library\Entities\Article;

class ArticleManagerPDO extends ArticleManager
{
	protected function add(Article $Article)
	{
		$requete = $this->dao->prepare("INSERT INTO Articles(Title,Content) VALUES(:Title,:Content)");
		$requete->bindValue(':Title', $Article->Title(), \PDO::PARAM_STR);
		$requete->bindValue(':Content', $Article->Content(), \PDO::PARAM_STR);
		$requete->execute();
		$Article->setId($this->dao->lastInsertId());
	}
	protected function modify(Article $Article)
	{
		$requete = $this->dao->prepare("UPDATE Articles SET Title=:Title,Content=:Content,Date=:Date WHERE id=:id");
		$requete->bindValue(':id', $Article->id(), \PDO::PARAM_INT);
		$requete->bindValue(':Title', $Article->Title(), \PDO::PARAM_STR);
		$requete->bindValue(':Content', $Article->Content(), \PDO::PARAM_STR);
		$requete->bindValue(':Date', $Article->Date(), \PDO::PARAM_STR);
		$requete->execute();
	}
	public function getListeOf()
	{
		$requete = $this->dao->prepare("SELECT * FROM Articles ");
		$requete->execute();
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Article');
		$Articles = $requete->fetchAll();
		return $Articles;
	}
	public function get($id)
	{
		$requete = $this->dao->prepare('SELECT * FROM Articles WHERE id=:id');
		$requete->bindValue(':id', (int)$id, \PDO::PARAM_INT);
		$requete->execute();
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Article');
		return $requete->fetch();
	}
	public function delete($id)
	{
		$requete = $this->dao->prepare("DELETE FROM Articles WHERE id=:id");
		$requete->bindValue(':id', (int)$id, \PDO::PARAM_INT);
		$requete->execute();
	}
	public function search($Title)
	{
		$requete = $this->dao->prepare("SELECT * FROM Articles WHERE Title LIKE '%$Title%' ");
		$requete->execute();
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Article');
		$result = $requete->fetchAll();
		return $result;
	}
}
