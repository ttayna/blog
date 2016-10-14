<?php
/**
 * Created by PhpStorm.
 * User: Tayna
 * Date: 12.10.2016
 * Time: 0:27
 */

namespace kernel;

/**
 * Class Model
 * @package kernel
 */
abstract class Model
{
    CONST LIMIT = 10;

    /** @var \PDO */
    public $dbh;

    /** @var string */
    protected $tableName;

    /**
     * Model constructor.
     * @param $db
     */
    function __construct($db)
    {
        $this->dbh = $db->dbh;
    }

    /**
     * Fetch array of Models
     * @param int $limit
     * @param int $position
     * @return mixed
     */
    abstract public function fetchList($limit = self::LIMIT, $position = 0);

    /**
     * Fetch one Model
     * @param $id
     * @return mixed
     */
    abstract public function fetchById($id);

    /**
     * Create new item
     * @param $data
     * @return mixed
     */
    abstract public function create($data);

    /**
     * Update the item
     * @param $id
     * @param $data
     * @return mixed
     */
    abstract public function update($id, $data);

    /**
     * Delete the item
     * @param $id
     * @return mixed
     */
    abstract public function delete($id);
}