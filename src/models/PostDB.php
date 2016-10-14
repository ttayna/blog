<?php
/**
 * Created by PhpStorm.
 * User: Tayna
 * Date: 10.10.2016
 * Time: 22:17
 */
namespace models;

use kernel\Model;

/**
 * Class PostDB
 * @package models
 */
class PostDB extends Model
{
    /** @var string */
    protected $tableName = "post";

    /**
     * Fetch array of Posts
     * @param int $limit
     * @param int $offset
     * @param string $orderBy
     * @return array
     */
    public function fetchList($limit = self::LIMIT, $offset = 0, $orderBy = "created")
    {
        $sth = $this->dbh->prepare("SELECT * FROM `{$this->tableName}` ORDER BY `$orderBy` DESC LIMIT $limit OFFSET $offset ;");
        $sth->execute();

        return $sth->fetchAll(\PDO::FETCH_CLASS, "models\\Post");
    }

    /**
     * Fetch one Post by Id
     * @param $id
     * @return mixed
     */
    public function fetchById($id)
    {
        $sth = $this->dbh->prepare("SELECT * FROM `{$this->tableName}` WHERE `id` = $id");
        $sth->execute();

        return $sth->fetchObject("models\\Post");
    }

    /**
     * Create Post
     * @param $data
     * @return string
     */
    public function create($data)
    {
        $sth = $this->dbh->prepare("INSERT INTO `{$this->tableName}` (`author`, `title`, `message`) VALUES (:author, :title, :message)");
        $sth->bindParam(':author', $data['author']);
        $sth->bindParam(':title', $data['title']);
        $sth->bindParam(':message', $data['message']);

        $sth->execute();

        return $this->dbh->lastInsertId();
    }

    /**
     * Update Post
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data)
    {
        $sth = $this->dbh->prepare("UPDATE `{$this->tableName}` SET `author` = :author, `title` = :title, `message` = :message WHERE id = $id;");
        $sth->bindParam(':author', $data['author']);
        $sth->bindParam(':title', $data['title']);
        $sth->bindParam(':message', $data['message']);

        return $sth->execute();
    }

    /**
     * Count of Posts
     * @return int
     */
    public function count()
    {
        $sth = $this->dbh->prepare("SELECT count(`id`) FROM `{$this->tableName}`;");
        $sth->execute();

        return (int)$sth->fetchColumn();
    }

    /**
     * Delete Post
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $sth = $this->dbh->prepare("DELETE FROM `{$this->tableName}` WHERE `id` = $id ;");

        return $sth->execute();
    }
}