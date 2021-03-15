<?php

namespace app\core;
class Application
{
    // create a static property to safe the root directory
    public static string $ROOT_DIR;


    public Router $router;
    public Request $request;
    public Response $response;

    public static Application $app;

    public Controller $controller;
    public Database  $db;

    public function __construct($rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        // this create new instances of the different properties in the application
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);

        $this->db = new Database($config['db']);
    }
    public function run()
    {
        echo $this->router->resolve();

    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }
}