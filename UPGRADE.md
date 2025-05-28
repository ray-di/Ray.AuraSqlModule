# UPGRADE

## From v1.x to v2.x

### Overview

Ray.AuraSqlModule v2.x is released to support PHP 8.4+, which introduced breaking changes in PDO connection handling. This major version bump is **only** due to dependency updates and **does not include any API or interface changes**.

### What Changed

- **PHP version requirement**: `^8.1` → `^8.4`
- **Aura.Sql dependency**: `^5.0` → `^6.0`
- **Internal PDO compatibility**: Updated to work with PHP 8.4's PDO changes

### What Didn't Change

- ✅ **All public APIs remain identical**
- ✅ **No interface changes**
- ✅ **No behavioral changes**
- ✅ **No configuration changes**
- ✅ **No breaking changes in your application code**

### Migration Guide

#### Step 1: Check Your PHP Version

**If you're using PHP 8.4+:**
```bash
composer require ray/aura-sql-module:^2.0
```

**If you're using PHP 8.1-8.3:**
```bash
composer require ray/aura-sql-module:^1.0
```

#### Step 2: Update Dependencies

No code changes are required. Simply update your `composer.json`:

```diff
{
    "require": {
-       "ray/aura-sql-module": "^1.0"
+       "ray/aura-sql-module": "^2.0"
    }
}
```

#### Step 3: Test Your Application

Since there are no API changes, your existing code should work without modification. However, we recommend running your test suite to ensure everything works as expected with the updated dependencies.

### Version Compatibility

| Ray.AuraSqlModule | PHP Version | Aura.Sql | Status |
|-------------------|-------------|----------|--------|
| v1.x              | ^8.1        | ^5.0     | Maintained |
| v2.x              | ^8.4        | ^6.0     | Current |

### Support Policy

- **v1.x**: Continues to be maintained for PHP 8.1-8.3 users
- **v2.x**: New development for PHP 8.4+ users

### Frequently Asked Questions

#### Q: Do I need to change my code?
**A: No.** All public APIs and interfaces remain exactly the same.

#### Q: Why the major version bump?
**A: PHP 8.4 introduced breaking changes in PDO that required Aura.Sql v6. Following semantic versioning, we bumped the major version even though no user-facing changes were made.**

#### Q: Can I stay on v1.x?
**A: Yes.** If you're using PHP 8.1-8.3, you can continue using v1.x, which will be maintained.

#### Q: When should I upgrade?
**A: Only when you upgrade to PHP 8.4+.** There's no urgency if you're on PHP 8.1-8.3.

### Need Help?

If you encounter any issues during the upgrade:

1. Check that you're using the correct version for your PHP version
2. Ensure all dependencies are properly updated
3. [Open an issue](https://github.com/ray-di/Ray.AuraSqlModule/issues) if you find any problems

### Technical Details

The upgrade addresses PDO connection signature changes introduced in PHP 8.4. The underlying Aura.Sql library handles these changes transparently, maintaining full backward compatibility at the application level.
