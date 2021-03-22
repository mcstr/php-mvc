<?php


namespace app\core;


class View
{
    public string $title = '';

    public function renderView($view, $params = [])
    {
        // include the view to render

        $viewContent = $this->renderOnlyView($view, $params);
        //create a method to return the layout content

        $layoutContent = $this->layoutContent();

        return str_replace('{{content}}', $viewContent, $layoutContent);

    }

    public function renderContent($viewContent)
    {
        //create a method to return the layout content

        $layoutContent = $this->layoutContent();

        // include the view to render

        return str_replace('{{content}}', $viewContent, $layoutContent);

    }

    protected function layoutContent()
    {

        $layout = Application::$app->layout;
        if (Application::$app->controller) {
            $layout = Application::$app -> controller ->layout;
        }

        ob_start();
        include_once  Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }
    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}