<?php

namespace TestUtils;

use Zend\Expressive\Template\TemplateRendererInterface;

class TemplateRendererStub implements TemplateRendererInterface
{
    /**
     * @var string
     */
    public $templateName = '';

    /**
     * @var string
     */
    public $layout = '';

    /**
     * @var array
     */
    public $data = [];

    public function render($name, $params = [])
    {
        $this->templateName = $name;
        $this->layout = $params['layout'];
        $this->data = $params['data'];

        return '';
    }

    public function addPath($path, $namespace = null)
    {
    }

    public function getPaths()
    {
    }

    public function addDefaultParam($templateName, $param, $value)
    {
    }
}
