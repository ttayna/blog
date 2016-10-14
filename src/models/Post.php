<?php
/**
 * Created by PhpStorm.
 * User: Tayna
 * Date: 10.10.2016
 * Time: 22:17
 */
namespace models;

/**
 * Class Post
 * @package models
 */
class Post
{
    /** @var int */
    private $id;
    /** @var string */
    private $author;
    /** @var string */
    private $title;
    /** @var string */
    private $message;
    /** @var array */

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->{$name};
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        if (!empty($this->{$name})) {
            return true;
        }

        return false;
    }
}