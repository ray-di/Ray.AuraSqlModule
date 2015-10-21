<?php

namespace Ray\AuraSqlModule;

class FakePagerInject
{
    use AuraSqlPagerInject;
    use AuraSqlQueryPagerInject;

    public function get()
    {
        return [
            $this->pagerFactory,
            $this->queryPagerFactory,
        ];
    }
}
