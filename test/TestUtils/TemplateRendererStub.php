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

    /**
     * @var array
     */
    public $errors = [];

    public function render($name, $params = [])
    {
        $this->templateName = $name;
        $this->layout = isset($params['layout']) ? $params['layout'] : '';
        $this->data = $params['data'];
        $this->errors = isset($params['errors']) ? $params['errors'] : [];

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
