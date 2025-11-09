## [1.16.0] - 2025-11-10

### Changed
- **Code Modernization**: Converted annotation classes to readonly classes
  - `Ray\AuraSqlModule\Annotation\Transactional`
  - `Ray\AuraSqlModule\Annotation\PagerViewOption`
  - `Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig`
  - `Ray\AuraSqlModule\Annotation\Read`
- **Doctrine Annotations Cleanup**: Removed all remaining Doctrine annotation syntax from docblocks in source and test files

### Fixed
- **Migration Tool**: Fixed `rector-migrate.php` to work correctly with user projects
  - Removed hardcoded `withPaths()` that prevented migration from working
  - Users can now specify target paths via command line arguments
  - Added documentation for processing multiple directories
  - Matches pattern used in Ray.PsrCacheModule (PR #32)
- **Type Annotations**: Fixed PDO options type from `array<string>` to `array<string, mixed>` in 8 files

### Note
- Migration guide has been verified to work correctly with real user projects
- All annotation classes are now readonly (PHP 8.2+ feature)
- **This is a critical fix** for users migrating from annotations to attributes

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
