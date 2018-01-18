<?php

namespace Game\Application;

class Application
{
    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * All of the registered service providers.
     *
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }

        array_walk($this->serviceProviders, function (ServiceProviderInterface $provider) {
            return $provider->boot();
        });

        $this->booted = true;
    }

    /**
     * Register a service provider with the application.
     *
     * @param ServiceProviderInterface|string $provider
     * @return ServiceProviderInterface
     */
    public function register($provider)
    {
        $provider = $this->getService($provider);

        $this->markAsRegistered($provider);

        return $provider;
    }

    /**
     * Mark the given provider as registered.
     *
     * @param ServiceProviderInterface $provider
     * @return void
     */
    private function markAsRegistered($provider)
    {
        $this->trigger('Application', get_class($provider), [$provider]);

        $this->serviceProviders[] = $provider;
    }

    public function getService($name)
    {

    }

    public function setService($name, $instance)
    {

    }

    public function hasService($name)
    {

    }

    public function trigger($name, $context)
    {

    }

    public function attach($scope, $name, $callback)
    {

    }
}