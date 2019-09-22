<?php

use Arlen\Omnicore\ClientFactory;
use GuzzleHttp\Client as GuzzleHttp;
use Arlen\Omnicore\LaravelClient as OmnicoreClient;
use Arlen\Omnicore\Facades\Omnicored as OmnicoredFacade;

class OmnicoredTest extends TestCase
{
    /**
     * Assert that configs are equal.
     *
     * @param  \Arlen\Omnicore\Client  $client
     * @param  array  $config
     *
     * @return void
     */
    protected function assertConfigEquals(OmnicoreClient $client, array $config)
    {
        $this->assertEquals($config['scheme'], $client->getConfig()['scheme']);
        $this->assertEquals($config['host'], $client->getConfig()['host']);
        $this->assertEquals($config['port'], $client->getConfig()['port']);
        $this->assertNotNull($client->getConfig()['user']);
        $this->assertNotNull($client->getConfig()['password']);
        $this->assertEquals($config['user'], $client->getConfig()['user']);
        $this->assertEquals($config['password'], $client->getConfig()['password']);
    }

    /**
     * Test service provider.
     *
     * @return void
     */
    public function testServiceIsAvailable()
    {
        $this->assertInstanceOf(
            ClientFactory::class, $this->app['omnicored']
        );

        $this->assertInstanceOf(
            OmnicoreClient::class, $this->app['omnicored.client']
        );

        $this->assertTrue($this->app->bound('omnicored'));
        $this->assertTrue($this->app->bound('omnicored.client'));
    }

    /**
     * Test facade.
     *
     * @return void
     */
    public function testFacade()
    {
        $this->assertInstanceOf(
            ClientFactory::class,
            OmnicoredFacade::getFacadeRoot()
        );

        $this->assertInstanceOf(
            OmnicoreClient::class,
            OmnicoredFacade::getFacadeRoot()->client()
        );

        $this->assertInstanceOf(
            OmnicoreClient::class,
            OmnicoredFacade::getFacadeRoot()->client('default')
        );
    }

    /**
     * Test helper.
     *
     * @return void
     */
    public function testHelper()
    {
        $this->assertInstanceOf(
            ClientFactory::class, omnicored()
        );

        $this->assertInstanceOf(
            OmnicoreClient::class, omnicored()->client()
        );

        $this->assertInstanceOf(
            OmnicoreClient::class, omnicored()->client('default')
        );
    }

    /**
     * Test trait.
     *
     * @return void
     */
    public function testTrait()
    {
        $this->assertInstanceOf(
            ClientFactory::class, $this->omnicored()
        );

        $this->assertInstanceOf(
            OmnicoreClient::class, $this->omnicored()->client()
        );

        $this->assertInstanceOf(
            OmnicoreClient::class, $this->omnicored()->client('default')
        );
    }

    /**
     * Test bitcoin config.
     *
     * @return void
     *
     * @dataProvider nameProvider
     */
    public function testConfig($name)
    {
        $this->assertConfigEquals(
            omnicored()->client($name),
            config("omnicored.$name")
        );
    }

    /**
     * Name provider for config test.
     *
     * @return array
     */
    public function nameProvider()
    {
        return [
            ['default'],
            ['litecoin'],
        ];
    }

    /**
     * Test with non existent config.
     *
     * @return void
     */
    public function testNonExistentConfig()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Could not find client configuration [nonexistent]');

        $config = omnicored()->client('nonexistent')->getConfig();
    }

    /**
     * Test with legacy config format.
     *
     * @return void
     */
    public function testLegacyConfig()
    {
        config()->set('omnicored', [
            'scheme'   => 'http',
            'host'     => 'localhost',
            'port'     => 8332,
            'user'     => 'testuser3',
            'password' => 'testpass3',
            'ca'       => null,
        ]);

        $this->assertConfigEquals(omnicored()->client(), config('omnicored'));
        $this->assertLogContains('You are using legacy config format');
    }

    /**
     * Test magic call to client through factory.
     *
     * @return void
     */
    public function testMagicCall()
    {
        $this->assertInstanceOf(GuzzleHttp::class, omnicored()->getClient());
    }

    /**
     * Test making new client instance.
     *
     * @return void
     */
    public function testFactoryMake()
    {
        $config = [
            'scheme'   => 'http',
            'host'     => '127.0.0.3',
            'port'     => 18332,
            'user'     => 'testuser3',
            'password' => 'testpass3',
        ];

        $this->assertConfigEquals(omnicored()->make($config), $config);
    }
}
