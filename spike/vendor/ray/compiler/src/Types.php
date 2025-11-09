<?php

declare(strict_types=1);

namespace Ray\Compiler;

use Ray\Di\Annotation\ScriptDir;
use Ray\Di\InjectorInterface;

/**
 * Type definitions for Ray.Compiler
 *
 * @psalm-type ScriptDir = non-empty-string
 * @psalm-type Ip = array{0: string, 1: string, 2: string}
 * @psalm-type Singletons = array<string, object>
 * @psalm-type Prottype = callable(string, Ip): mixed
 * @psalm-type Singleton = callable(string, Ip): mixed
 * @psalm-type InjectionPoint = callable(): InjectionPoint
 * @psalm-type Injector = callable(): InjectorInterface
 * @psalm-type ScriptDirs = list<ScriptDir>
 * @psalm-type Scripts = array<string, string>
 * @psalm-type SavedSingletons = list<class-string>
 * @psalm-type ConstructorParams = list<mixed>
 * @psalm-type BindingCode = list<string>
 * @psalm-type SetterCode = list<string>
 * @psalm-type IpParameters = list<object|null>
 */
final class Types
{
    /** @codeCoverageIgnore */
    private function __construct()
    {
    }
}
