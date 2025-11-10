## [1.13.6] - 2025-11-10

### Fixed
- **Migration Tool**: Fixed `rector-migrate.php` to work correctly with user projects
  - Removed hardcoded `withPaths()` that prevented migration from working
  - Users can now specify target paths via command line arguments
  - Added documentation for processing multiple directories
  - Matches pattern used in Ray.PsrCacheModule (PR #32)

### Note
- This is a **critical fix** for users migrating from annotations to attributes
- Migration guide has been verified to work correctly with real user projects

## [1.13.5] - 2025-11-09

### Fixed
- **Documentation**: Corrected incorrect version references in `ANNOTATION_TO_ATTRIBUTE.md`

## [1.13.4] - 2025-11-09

### Changed
- **Removed doctrine/annotations**: Eliminated abandoned dependency from PHP 8.0-8.3 codebase
- **Updated aura/sqlquery**: Changed constraint from `3.x-dev` to `^3.0` for stability
- **CI Update**: Test matrix now covers PHP 8.0, 8.1, 8.2, and 8.3 only

### Added
- **Migration Tools**: Added `rector-migrate.php` for automated annotation-to-attribute migration
- **Migration Guide**: Added `ANNOTATION_TO_ATTRIBUTE.md` with comprehensive migration instructions

### Note
- **PHP 8.0-8.3 Support**: This version supports PHP 8.0, 8.1, 8.2, and 8.3
- **Migration Path**: Users can migrate to native PHP 8 attributes using the provided Rector configuration
- **For PHP 8.4+**: Use version 1.15.0 or later

## Why This Change?

The `doctrine/annotations` package has been officially **abandoned** by its maintainers and will no longer receive updates or security patches. This change:

- Eliminates security and compatibility risks from the abandoned package
- Provides a clear migration path to PHP 8 attributes
- Maintains support for PHP 8.0-8.3 users

## Migration to Attributes (Optional)

While not required for PHP 8.0-8.3, you can optionally migrate to native PHP 8 attributes:

```bash
# Preview changes
vendor/bin/rector process src --config=vendor/ray/aura-sql-module/rector-migrate.php --dry-run

# Apply migration
vendor/bin/rector process src --config=vendor/ray/aura-sql-module/rector-migrate.php
```

See `ANNOTATION_TO_ATTRIBUTE.md` for detailed migration guide.
