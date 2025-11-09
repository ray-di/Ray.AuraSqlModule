# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.12.1] - 2025-10-27

### Fixed
- Remove incorrect @deprecated annotation from DiCompileModule

## [1.12.0] - 2025-10-26

### Changed
- Use `override()` in Compiler and remove BuiltinModule from CompilerModule
- Use `override()` instead of `install()` for CompilerModule [#116]
- Move Code4Dependency to src-deprecated and exclude from Psalm

### Added
- Introduce Code4Dependency class and incorporate into DependencyCode
- Add Symfony PHP 8.3 polyfill and Override attributes
- Add PHP 8.5 support to CI
- Add LLM documentation for Ray.Compiler [#118]

### Fixed
- Remove duplicated class [#116]
- Fix PHP 7.2 compatibility in CompilerModuleOverrideTest
- Use domain types from Types.php and fix PHPStan type errors

## [1.11.0] - 2024-12-XX

Previous releases (prior to 1.11.0) are not documented in this changelog.

[1.12.1]: https://github.com/ray-di/Ray.Compiler/compare/1.12.0...1.12.1
[1.12.0]: https://github.com/ray-di/Ray.Compiler/compare/1.11.0...1.12.0
[1.11.0]: https://github.com/ray-di/Ray.Compiler/releases/tag/1.11.0
