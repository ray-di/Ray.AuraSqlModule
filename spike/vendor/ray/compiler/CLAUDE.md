# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Ray.Compiler is a dependency injection compiler for Ray.Di that pre-compiles DI bindings into executable PHP code. It transforms Ray.Di's complex dependency resolution into simple PHP scripts, replacing the full-featured Ray.Di Injector with a minimal `CompiledInjector` that does nothing but execute pre-compiled code.

**Two-Phase Architecture:**
- **Compile-time**: Uses Ray.Di's full dependency resolution (reflection, binding analysis, AOP weaving)
- **Runtime**: Minimal `CompiledInjector` simply executes pre-generated scripts—no reflection, no binding resolution, no dependency graph traversal

## Prerequisites

Understanding Ray.Compiler requires understanding Ray.Di (the dependency injection framework being compiled).

**Ray.Di Complete Documentation (for LLMs):**
https://ray-di.github.io/llms-full.txt

This document explains Ray.Di's module system, dependency resolution, scope management, and AOP—all concepts that Ray.Compiler transforms into executable PHP code at compile-time, allowing runtime to use the minimal CompiledInjector instead of Ray.Di's full Injector.

## Core Architecture

### Compilation Flow

1. **Compiler** (`src/Compiler.php`): Entry point that compiles Ray.Di modules into Scripts
   - Acquires file lock for thread safety during compilation
   - Uses ContainerFactory to create dependency container
   - Uses CompileVisitor to traverse and convert dependencies to PHP code
   - Saves compiled scripts to target directory via Scripts class

2. **CompileVisitor** (`src/CompileVisitor.php`): Implements visitor pattern to traverse dependency graph
   - Visits dependencies, providers, instances, AOP bindings
   - Delegates to InstanceScript for code generation
   - Distinguishes between singleton and prototype scopes

3. **InstanceScript** (`src/InstanceScript.php`): Generates PHP code for dependency instantiation
   - Handles constructor injection via `pushClass()`
   - Handles setter injection via `pushMethod()`
   - Handles AOP bindings via `pushAspectBind()`
   - Manages context for provider bindings
   - Generates final script with proper scope handling

4. **Code4Dependency** (`src/Code4Dependency.php`): Alternative code generation approach
   - Uses PrivateProperty to access internal dependency state
   - Generates code for constructor args, setters, and AOP bindings
   - Recently introduced to handle dependency code generation

5. **Scripts** (`src/Scripts.php`): Container for generated scripts
   - Stores script index -> PHP code mapping
   - Writes scripts to files named by dependency index (e.g., `Interface-name.php`)

### Runtime Execution

1. **CompiledInjector** (`src/CompiledInjector.php`): Minimal injector that executes pre-compiled code
   - **Does NOT perform dependency resolution** - all resolution happens at compile-time
   - **Does NOT use reflection** - all necessary metadata embedded in compiled scripts
   - `getInstance()` simply constructs file path and executes script via `require`
   - Manages singleton instances in `$singletons` array (passed by reference to scripts)
   - Registers autoloader for compiled classes
   - Script files named as: `ClassName-bindingName.php`
   - This minimal approach eliminates all Ray.Di runtime overhead

2. **Scope Functions** (`src-function/`): Helper functions used in compiled scripts
   - `singleton()`: Returns cached instance or requires script file (singleton scope)
   - `prototype()`: Always requires script file for new instance (prototype scope)
   - Both use `$scriptDir`, `$singletons`, `$dependencyIndex`, and optional `$ip` (injection point)

### Directory Structure

- `src/`: Main source code
- `src-deprecated/`: Legacy code maintained for backwards compatibility
- `src-function/`: Global functions for singleton/prototype scope handling
- `tests/`: PHPUnit tests with `tests/Fake/` for test fixtures
- Compiled scripts output to user-specified directory (e.g., `tmp/di/`, `.di/`)

## Development Commands

### Testing
```bash
# Run tests
composer test

# Run tests with coverage (pcov)
composer pcov

# Run all tests, CS, and static analysis
composer tests
```

### Code Quality
```bash
# Check coding standards
composer cs

# Fix coding standards
composer cs-fix

# Run static analysis (Psalm + PHPStan)
composer sa

# Clean caches
composer clean
```

### Full Build
```bash
# Run complete build with metrics
composer build
```

### Running Single Tests
```bash
# Run specific test class
vendor/bin/phpunit tests/CompilerTest.php

# Run specific test method
vendor/bin/phpunit --filter testMethodName tests/CompilerTest.php
```

## Key Concepts

### Dependency Index Format
Dependencies are identified as `Interface-BindingName` (e.g., `FooInterface-`, `BarInterface-named`). The `-ANY` suffix is used for unnamed bindings. This index is used for:
- Script file naming (with backslashes replaced by underscores)
- Singleton storage keys
- Dependency lookups

### Injection Points
When a dependency requires injection point information, the compiled code includes `$ip` array with:
- `[0]`: Declaring class name
- `[1]`: Declaring method/function name
- `[2]`: Parameter name

InjectionPoint class reconstructs ReflectionParameter from this array.

### Scope Handling
- **Singleton**: Instance created once, cached in `$singletons[$dependencyIndex]`
- **Prototype**: New instance created on each `getInstance()` call
- Scope determined during compilation and embedded in generated scripts

### AOP (Aspect-Oriented Programming)
- Bindings stored in `$instance->bindings` array: `['methodName' => [interceptor1, interceptor2]]`
- Interceptors are themselves dependencies loaded via `singleton()` with `-` suffix

## PHP Version Support
Requires PHP 7.2+ or 8.0+. Code uses both annotations and attributes (PHP 8 attributes via `#[...]` syntax).

## Compiled Output Should Not Be Committed
The `/tmp/di/` or similar compiled script directories should be in `.gitignore`. Compilation happens during deployment/build, not development.