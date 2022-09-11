<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

use function is_array;
use function is_object;
use function method_exists;
use function strtr;

class AuraSqlProfileModuleTest extends TestCase
{
    /** @var array<string> */
    public static $log = [];

    public function testModule(): ExtendedPdoInterface
    {
        $module = new class extends AbstractModule {
            protected function configure()
            {
                $this->install(new AuraSqlModule('sqlite::memory:'));
                $this->install(new AuraSqlProfileModule());
                $this->bind(LoggerInterface::class)->toInstance(
                    new class extends AbstractLogger {
                        /** @inheritDoc */
                        public function log($level, $message, array $context = [])
                        {
                            $replace = [];
                            foreach ($context as $key => $val) {
                                if (! is_array($val) && (! is_object($val) || method_exists($val, '__toString'))) {
                                    $replace['{' . $key . '}'] = $val;
                                }
                            }

                            AuraSqlProfileModuleTest::$log[] = strtr($message, $replace);
                        }
                    }
                );
            }
        };
        $instance = (new Injector($module, __DIR__ . '/tmp'))->getInstance(ExtendedPdoInterface::class);
        $this->assertInstanceOf(ExtendedPdoInterface::class, $instance);

        return $instance;
    }

    /**
     * @depends testModule
     */
    public function testLog(ExtendedPdoInterface $pdo)
    {
        $pdo->exec(/** @lang sql */'CREATE TABLE user(name, age)');
        $pdo->perform(/** @lang sql */'INSERT INTO user (name, age) VALUES (?, ?)', ['koriym', 18]);
        $pdo->perform(/** @lang sql */'SELECT * FROM user');
        $this->assertCount(4, self::$log);
    }
}
