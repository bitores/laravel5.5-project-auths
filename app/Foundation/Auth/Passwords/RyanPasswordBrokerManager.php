<?php

namespace App\Foundation\Auth\Passwords;

use App\Services\EmailService;
use InvalidArgumentException;
use Illuminate\Contracts\Auth\PasswordBrokerFactory as FactoryContract;
use Illuminate\Auth\Passwords\PasswordBrokerManager as PasswordBrokerManager;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;

class RyanPasswordBrokerManager extends PasswordBrokerManager
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The array of created "drivers".
     *
     * @var array
     */
    protected $brokers = [];

    protected $mailer;

    /**
     * Create a new PasswordBroker manager instance.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->mailer = new EmailService();
    }

    /**
     * Attempt to get the broker from the local cache.
     *
     * @param  string $name
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return isset($this->brokers[$name]) ? $this->brokers[$name] : $this->brokers[$name] = $this->resolve($name);
    }

    /**
     * Resolve the given broker.
     *
     * @param  string $name
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if(is_null($config)) {
            throw new InvalidArgumentException("Password resetter [{$name}] is not defined.");
        }
      
        //这里实例化我们自定义的RyanPasswordBroker来完成密码重置逻辑
        return new RyanPasswordBroker($this->createTokenRepository($config), $this->app['auth']->createUserProvider($config['provider']), $this->mailer, $config['email']);
    }
}
