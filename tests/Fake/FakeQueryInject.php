<?php

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;

class FakeQueryInject
{
    use AuraSqlSelectInject;
    use AuraSqlInsertInject;
    use AuraSqlUpdateInject;
    use AuraSqlDeleteInject;

    /** @param string $db */
    #[AuraSqlQueryConfig]
    public function __construct(private string $db)
    {
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
