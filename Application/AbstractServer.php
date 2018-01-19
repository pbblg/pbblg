<?php

namespace Game\Application;

abstract class AbstractServer
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @return Application
     */
    public function getApp()
    {
        if ($this->app !== null) {
            return $this->app;
        }

        date_default_timezone_set('UTC');

        $app = new Application();

//        $app->setService('config', $this->config);

//        $this->registerLogger($app);

//        $this->registerCache($app);

//        $app->register('Game\Application\Database\DatabaseServiceProvider');
//        $app->register('Game\Application\Settings\SettingsServiceProvider');
//        $app->register('Game\Application\Locale\LocaleServiceProvider');
//        $app->register('Game\Application\Bus\BusServiceProvider');
//        $app->register('Game\Application\Filesystem\FilesystemServiceProvider');
//        $app->register('Game\Application\View\ViewServiceProvider');

        $app->boot();

        $this->app = $app;

        return $app;
    }
}