<?php

namespace Ray\AuraSqlModule;

final class FakeEntity
{
    public string $id;
    public string $name;
    public string $post_content;

    public function __construct(
        string $id,
        string $name,
        string $post_content
    ){
        $this->id = $id;
        $this->name = $name;
        $this->post_content = $post_content;
    }
}
