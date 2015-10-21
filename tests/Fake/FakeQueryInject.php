<?php

namespace Ray\AuraSqlModule;

class FakeQueryInject
{
    use AuraSqlSelectInject;
    use AuraSqlInsertInject;
    use AuraSqlUpdateInject;
    use AuraSqlDeleteInject;

    public function get()
    {
        return [
            $this->select,
            $this->insert,
            $this->update,
            $this->delete
        ];
    }
}
