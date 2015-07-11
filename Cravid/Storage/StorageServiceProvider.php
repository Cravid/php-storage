<?php

namespace Cravid\Storage;

use Silex\Application;
use Silex\ServiceProviderInterface;

class StorageServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['storage'] = $app->share(function () use ($app) {
            $storage = new \Cravid\Storage\Storage($app['storage.drivers.config']);
            return $storage;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
