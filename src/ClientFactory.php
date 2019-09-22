<?php

declare(strict_types=1);

namespace Arlen\Omnicore;

use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use Arlen\Omnicore\LaravelClient as OmnicoreClient;

class ClientFactory
{
    /**
     * Client configurations.
     *
     * @var array
     */
    protected $config;

    /**
     * Laravel log writer.
     *
     * @var \Illuminate\Log\Writer
     */
    protected $logger;

    /**
     * Client instances.
     *
     * @var array
     */
    protected $clients = [];

    /**
     * Constructs client factory instance.
     *
     * @param  array                     $config
     * @param  \Psr\Log\LoggerInterface  $logger
     *
     * @return void
     */
    public function __construct(array $config, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Appends configuration array with default values.
     *
     * @param  array  $config
     *
     * @return array
     */
    protected function withDefaults(array $config = []) : array
    {
        return array_merge([
            'scheme'   => 'http',
            'host'     => 'localhost',
            'port'     => 8332,
            'user'     => null,
            'password' => null,
            'ca'       => null,
            'zeromq'   => null,
        ], $config);
    }

    /**
     * Gets client config by name.
     *
     * @param  string  $name
     *
     * @return array
     */
    public function getConfig(string $name = 'default') : array
    {
        if (isset($this->config['host']) && ! is_array($this->config['host'])) {
            $this->logger->warning(
                'laravel-omnicore: You are using legacy config format which '.
                'was deprecated and will be removed in the next version. '.
                'Please update your config file by running [php artisan '.
                'vendor:publish '.
                '--provider="Arlen\Omnicore\Providers\ServiceProvider" --force].'
            );

            return $this->withDefaults($this->config);
        }

        if (! array_key_exists($name, $this->config)) {
            throw new InvalidArgumentException(
                "Could not find client configuration [$name]"
            );
        }

        return $this->withDefaults($this->config[$name]);
    }

    /**
     * Gets client instance by name or creates if not exists.
     *
     * @param  string  $name
     *
     * @return \Arlen\Omnicore\Client
     */
    public function client(string $name = 'default') : OmnicoreClient
    {
        if (! array_key_exists($name, $this->clients)) {
            $config = $this->getConfig($name);

            $this->clients[$name] = $this->make($config);
        }

        return $this->clients[$name];
    }

    /**
     * Creates client instance.
     *
     * @param  array  $config
     *
     * @return \Arlen\Omnicore\Client
     */
    public function make(array $config = []) : OmnicoreClient
    {
        return new OmnicoreClient($config);
    }

    /**
     * Pass methods onto the default client.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->client()->{$method}(...$parameters);
    }
}
