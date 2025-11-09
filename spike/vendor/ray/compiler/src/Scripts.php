<?php

declare(strict_types=1);

namespace Ray\Compiler;

use Countable;
use Override;

use function count;
use function sprintf;
use function str_replace;

/** @psalm-import-type Scripts from Types */
final class Scripts implements Countable
{
    /** @var Scripts */
    private $scripts = [];

    public function add(string $index, string $script): void
    {
        $this->scripts[$index] = $script;
    }

    public function save(string $scriptDir): void
    {
        $template = <<<'EOL'
<?php
%s
EOL;
        $filePutContents = new FilePutContents();
        foreach ($this->scripts as $index => $script) {
            $file = sprintf('%s/%s.php', $scriptDir, str_replace('\\', '_', $index));
            $script = sprintf($template, $script);
            $filePutContents($file, $script);
        }
    }

    #[Override]
    public function count(): int
    {
        return count($this->scripts);
    }
}
