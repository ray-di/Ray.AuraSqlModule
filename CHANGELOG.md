## [1.17.1] - 2026-03-13

### Added
- Psalm taint annotations (`@psalm-taint-sink sql`) for SQL injection analysis (PR #89)

## [1.15.3] - 2025-11-10

### Fixed
- **Coding Standards**: Updated tools to properly detect PHP 8 Attributes
  - Upgraded `squizlabs/php_codesniffer` from ^3.13 to ^4.0
  - Upgraded `doctrine/coding-standard` from ^13.0 to ^14.0
  - Upgraded `slevomat/coding-standard` from 8.22.1 to 8.24.0
  - Fixed false positives where Attributes were reported as unused imports

### Note
- This update ensures coding standards tools correctly recognize PHP 8 Attributes

## [1.15.2] - 2025-11-10

### Fixed
- **Migration Tool**: Fixed `rector-migrate.php` to work correctly with user projects
  - Removed hardcoded `withPaths()` that prevented migration from working
  - Users can now specify target paths via command line arguments
  - Added documentation for processing multiple directories
  - Matches pattern used in Ray.PsrCacheModule (PR #32)
- **Type Annotations**: Fixed PDO options type from `array<string>` to `array<string, mixed>` in 8 files
- **Doctrine Annotations Cleanup**: Removed all remaining Doctrine annotation syntax from docblocks in source and test files

### Changed
- **Code Modernization**: Converted annotation classes to readonly classes
  - `Ray\AuraSqlModule\Annotation\Transactional`
  - `Ray\AuraSqlModule\Annotation\PagerViewOption`
  - `Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig`
  - `Ray\AuraSqlModule\Annotation\Read`

### Note
- **This is a critical fix** for users migrating from annotations to attributes
- Migration guide has been verified to work correctly with real user projects
- All annotation classes are now readonly (PHP 8.2+ feature)

## [1.15.1] - 2025-11-09

### Fixed
- **Documentation**: Corrected incorrect version references in `ANNOTATION_TO_ATTRIBUTE.md`

## [1.15.0] - 2025-11-09

### Changed
- **PHP 8.4 Requirement**: Updated minimum PHP version to ^8.4
- **Aura.Sql v6**: Updated dependency from ^5.0 to ^6.0 for PHP 8.4 compatibility
- **Removed doctrine/annotations**: Eliminated abandoned dependency, migrated to native PHP 8 attributes
- **Code Modernization**: Applied Rector refactoring for cleaner, modern PHP 8.4 code

### Added
- **Migration Tools**: Added `rector-migrate.php` for automated annotation-to-attribute migration
- **Migration Guide**: Added `ANNOTATION_TO_ATTRIBUTE.md` with comprehensive migration instructions
- **CI Enhancement**: Added Scrutinizer CI configuration for automated code quality analysis

### Note
- **Migration Required**: Applications using annotations must migrate to PHP 8 attributes
- Use provided Rector configuration for automated migration: `vendor/bin/rector process src --config=vendor/ray/aura-sql-module/rector-migrate.php`
- See `ANNOTATION_TO_ATTRIBUTE.md` for detailed migration guide
