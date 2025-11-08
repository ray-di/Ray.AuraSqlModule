## [1.18.0] - 2025-11-09

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
