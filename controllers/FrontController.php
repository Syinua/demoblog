<?php
/**
 * Description of FrontController
 *
 * @author http://syinua.com
 */
namespace Controller;

/**
 * Class FrontController
 * @package Controller
 */
class FrontController
{
    /**
     * @var string
     */
    protected $_controller;
    /**
     * @var string
     */
    protected $_action;
    /**
     * @var array
     */
    protected $_params;
    /**
     * @var
     */
    protected $_html;
    /**
     * @var null
     */
    static private $_instance = null;

    /**
     *
     */
    private function __clone()
    {
    }

    /**
     * @return FrontController|null
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     *
     */
    private function __construct()
    {
        $request = $_SERVER['REQUEST_URI'];
        $splits = explode('/', trim($request, '/'));
        $this->_controller = !empty($splits[0]) ? '\\Controller\\'.ucfirst(
                $splits[0]
            ).'Controller' : '\Controller\IndexController';
        $this->_action = !empty($splits[1]) ? $splits[1].'Action' : 'indexAction';
        if (!empty($splits[2])) {
            $keys = $values = array();
            for ($i = 2, $cnt = count($splits); $i < $cnt; $i++) {
                if ($i % 2 == 0) {
                    $keys[] = $splits[$i];
                } else {
                    $values[] = $splits[$i];
                }
            }
            $this->_params = (object)array_combine($keys, $values);
        }
    }

    /**
     *
     */
    public function siteRoute()
    {
        if (class_exists($this->getController())) {
            $rc = new \ReflectionClass($this->getController());
            if ($rc->implementsInterface('\Controller\IController')) {
                if ($rc->hasMethod($this->getAction())) {
                    $controller = $rc->newInstance();
                    $method = $rc->getMethod($this->getAction());
                    $method->invoke($controller);
                } else {
                    $this->render('404.twig');
                    //throw new Exception("Action");
                }
            } else {
                throw new \Exception("Interface");
            }
        } else {
            $this->render('404.twig');
            //throw new Exception("Controller");
        }
    }

    /**
     * @return object
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * @param $link
     * @param $array
     */
    public function render($link, $array = [])
    {
        $loader = new \Twig_Loader_Filesystem( realpath('../views'));
        $twig = new \Twig_Environment($loader, array(
//            'cache' => '/path/to/compilation_cache',
        ));
        $this->_html = $twig->render($link, $array);
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->_html;
    }
}