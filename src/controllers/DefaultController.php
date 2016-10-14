<?php
/**
 * Created by PhpStorm.
 * User: Tayna
 * Date: 10.10.2016
 * Time: 22:16
 */

namespace controllers;

use kernel\Controller;
use kernel\DB;
use models\PostDB;

/**
 * Class DefaultController
 * @package controllers
 */
class DefaultController extends Controller
{
    /** @var PostDB */
    private $model;
    /** @var string */
    protected $view;

    function __construct($view = null)
    {
        $this->view = $view;
        $this->model = new PostDB(new DB());
    }

    /**
     * Page with list of posts
     * @return bool
     */
    public function listAction()
    {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : PostDB::LIMIT;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

        return $this->render([
            'posts' => $this->model->fetchList($limit, $offset),
            'limit' => $limit,
            'offset' => $offset,
            'totalPosts' => $this->model->count(),
            'alert' => $this->getAlerts(['post_created', 'post_deleted', 'post_updated'])
        ]);
    }

    /**
     * Create a new post
     * @return bool
     */
    public function createAction()
    {
        if (isset($_POST["post"])) {
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'message' => $_POST['message'],
            ];
            // checking: all fields are required
            if (count(array_filter($data)) !== count($data)) {
                return $this->render([
                    'error' => 'required_fields',
                    'post' => array_merge($data)
                ]);
            }

            $id = $this->model->create($data);
            if ($id) {
                $_SESSION['post_created'] = true;
                header('Location: http://'.$_SERVER['HTTP_HOST'] . '/');
                exit();
            } else {
                return $this->render([
                    'error' => 'error'
                ]);
            }
        }

        return $this->render([
            'type' => 'create'
        ]);
    }

    /**
     * Edit a post
     * @return bool
     */
    public function editAction($id)
    {
        if (!$post = $this->model->fetchById($id)) {
            $this->error404();
        }

        if (isset($_POST["post"])) {
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'message' => $_POST['message'],
            ];
            // checking: all fields are required
            if (count(array_filter($data)) !== count($data)) {
                return $this->render([
                    'error' => 'required_fields',
                    'type' => 'edit',
                    'post' => array_merge($data, ['id' => $id])
                ]);
            }

            $id = $this->model->update($id, $data);

            if ($id) {
                $_SESSION['post_updated'] = true;
                header('Location: http://'.$_SERVER['HTTP_HOST']);
                exit();
            } else {
                return $this->render([
                    'error' => 'error'
                ]);
            }
        }

        return $this->render([
            'type' => 'edit',
            'post' => $post
        ]);
    }

    /**
     * Delete a post
     * @param $id
     * @return bool
     */
    public function deleteAction($id)
    {
        $result = $this->model->delete($id);
        if ($result) {
            $_SESSION['post_deleted'] = true;
            header('Location: http://'.$_SERVER['HTTP_HOST'] . '/');
            exit();
        }

        return $this->render([
            'error' => 'error'
        ]);
    }
}