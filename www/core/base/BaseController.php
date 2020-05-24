<?php


namespace core\base;

/**
 * Class BaseController
 */
class BaseController
{
    /** @var string Директория с представлениями */
    const BASE_VIEWS_DIR = ROOT_DIR . 'views/';
    /** @var string Директория с лейаутами */
    const LAYOUTS_DIR = ROOT_DIR . 'layouts/';

    /** @var string Название лейаута */
    private $layout;
    /** @var string Директори с представлениями относящимися к контроллеру */
    private $viewsDir;

    /**
     * BaseController constructor.
     *
     * @param string $layout
     */
    public function __construct($layout = 'default')
    {
        $this->viewsDir = self::BASE_VIEWS_DIR .
            strtolower(str_replace(
                'Controller',
                DIRECTORY_SEPARATOR,
                static::class
            ));

        $this->layout = $layout;
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

        $this->getLayoutHeader();
        $this->getContent($view, $data);
        $this->getLayOutFooter();

        return ob_clean();
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

        $this->getContent($view, $data);

        return ob_clean();
    }

    /**
     * Возвращает контент для отрисовки
     *
     * @param $view
     * @param array $data
     * @return mixed
     */
    private function getContent($view, $data = [])
    {
        extract($data);

        return require $this->viewsDir . $view . '.php';
    }

    /**
     * Возвращает хэдер
     *
     * @return mixed
     */
    private function getLayoutHeader()
    {
        return require self::LAYOUTS_DIR . $this->layout . '/header.php';
    }

    /**
     * Возвращает футер
     *
     * @return mixed
     */
    private function getLayoutFooter()
    {
        return require self::LAYOUTS_DIR . $this->layout . '/footer.php';
    }
}
