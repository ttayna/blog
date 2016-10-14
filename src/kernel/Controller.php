<?php
/**
 * Created by PhpStorm.
 * User: Tayna
 * Date: 14.10.2016
 * Time: 1:49
 */

namespace kernel;

/**
 * Class Controller
 * @package kernel
 */
class Controller
{
    CONST PATH_TWIG_TEMPLATES = '/../../src/views';
    CONST PATH_TWIG_CACHE = '/../../cache';

    /** @var  string */
    protected $view;

    /**
     * Get array of alerts for page
     * @param array $items
     * @return array
     */
    protected function getAlerts($items = [])
    {
        $alerts = [];
        foreach ($items as $item) {
            if (isset($_SESSION[$item])) {
                unset($_SESSION[$item]);
                $alerts[$item] = true;
            }
        }

        return $alerts;
    }

    /**
     * Page 404
     */
    public function error404()
    {
        header('HTTP/1.0 404 Not Found');
        $this->view = '404.html.twig';
        $this->render([]);
        exit();
    }

    /**
     * Rendering template
     * @param $data
     * @return bool
     */
    protected function render($data)
    {
        if (!$this->view) {
            die("Fatal error: view??");
        }
        $config = Config::getInstance();
        $loader = new \Twig_Loader_Filesystem(__DIR__ . self::PATH_TWIG_TEMPLATES);
        $twig = new \Twig_Environment(
            $loader, [
                'cache' => $config['cache'] ? __DIR__ . self::PATH_TWIG_CACHE : false,
                'debug' => $config['debug'],
            ]
        );
        if($config['debug']) {
            $twig->addExtension(new \Twig_Extension_Debug());
        }

        $template = $twig->loadTemplate($this->view);
        echo $template->render($data);

        return true;
    }
}