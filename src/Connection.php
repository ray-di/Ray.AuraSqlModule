<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;

/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
class Connection
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $password;

    /**
     * @var ExtendedPdo
     */
    private $pdo;

    /**
     * @param string $dsn
     * @param string $id
     * @param string $password
     */
    public function __construct($dsn, $id = null, $password = null)
    {
        $this->dsn = $dsn;
        $this->id = $id;
        $this->password = $password;
    }

    public function __invoke()
    {
        if (!$this->pdo) {
            $this->pdo = new ExtendedPdo($this->dsn, $this->id, $this->password);
        }

        return $this->pdo;
    }
}
