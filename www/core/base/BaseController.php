<?php


namespace core\base;

/**
 * Class BaseController
 */
class BaseController
{
    /** @var array Название лейаута */
    private $layout;
    /** @var string Директори с представлениями относящимися к контроллеру */
    private $viewsDir;

    /**
     * BaseController constructor.
     *
     * @param string $layoutName
     */
    public function __construct($layoutName = 'default')
    {
        $this->viewsDir = ROOT_DIR . 'views/' .
            strtolower(str_replace(
                ['Controller', 'controllers\\'],
                '',
                static::class
            )) . '/';

        $this->layout = [
            'header' => ROOT_DIR . 'layouts/' . $layoutName . '/header.php',
            'footer' => ROOT_DIR . 'layouts/' . $layoutName . '/footer.php'
        ];
    }

    /**
     * Отрисовывает представаление с лейаутом
     *
     * @param $view
     * @param array $data
     */
    public function render($view, $data = [])
    {
        ob_start();

        $this->getFileContent($this->layout['header'], $data);
        $this->getFileContent($this->viewsDir . $view . '.php', $data);
        $this->getFileContent($this->layout['footer'], $data);

        echo ob_get_clean();
    }

    /**
     * Отрисовывает представление без лейаута
     *
     * @param $view
     * @param array $data
     */
    public function renderPartial($view, $data = [])
    {
        ob_start();

        $this->getFileContent($this->viewsDir . $view . '.php', $data);

        echo ob_get_clean();
    }

    /**
     * Возвращает контент для отрисовки
     *
     * @param string $path
     * @param array $data
     * @return mixed
     */
    private function getFileContent($path, $data)
    {
        if (!empty($data)) {
            extract($data);
        }

        return require $path;
    }
}
