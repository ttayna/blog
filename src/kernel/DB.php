<?php
/**
 * Created by PhpStorm.
 * User: Tayna
 * Date: 13.10.2016
 * Time: 23:16
 */

namespace kernel;

/**
 * Class DB
 * @package kernel
 */
class DB
{
    /** @var \PDO */
    public $dbh;

    /**
     * DB constructor.
     * @throws \Exception
     */
    function __construct()
    {
        $config = Config::getInstance();
        try {
            $this->dbh = new \PDO(
                'mysql:host=' . $config['host'] . ';dbname=' . $config['database'],
                $config['user'],
                $config['pass']
            );
        } catch (\PDOException $e) {
            $msg = $e->getMessage();
            throw new \Exception("Failed to connect to MySQL: (\" . $msg  . \")");
        }
    }
}