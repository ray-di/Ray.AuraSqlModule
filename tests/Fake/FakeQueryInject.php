<?php

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;

class FakeQueryInject
{
    use AuraSqlSelectInject;
    use AuraSqlInsertInject;
    use AuraSqlUpdateInject;
    use AuraSqlDeleteInject;

    /**
     * @var string
     */
    private $db;

    /**
     * @AuraSqlQueryConfig
     *
     * @param string $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get()
    {
        return [
            $this->db,
            $this->select,
            $this->insert,
            $this->update,
            $this->delete
        ];
    }
}
