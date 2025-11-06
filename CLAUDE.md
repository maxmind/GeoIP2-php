# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**GeoIP2-php** is MaxMind's official PHP client library for:
- **GeoIP2/GeoLite2 Web Services**: Country, City, and Insights endpoints
- **GeoIP2/GeoLite2 Databases**: Local MMDB file reading for various database types (City, Country, ASN, Anonymous IP, Anonymous Plus, ISP, etc.)

The library provides both web service clients and database readers that return strongly-typed model objects containing geographic, ISP, anonymizer, and other IP-related data.

**Key Technologies:**
- PHP 8.1+ (uses modern PHP features like readonly properties and strict types)
- MaxMind DB Reader for binary database files
- MaxMind Web Service Common for HTTP client functionality
- PHPUnit for testing
- php-cs-fixer, phpcs, and phpstan for code quality

## Code Architecture

### Package Structure

```
GeoIp2/
├── Model/              # Response models (City, Insights, AnonymousIp, etc.)
├── Record/             # Data records (City, Location, Traits, etc.)
├── Exception/          # Custom exceptions for error handling
├── Database/Reader     # Local MMDB file reader
├── WebService/Client   # HTTP client for MaxMind web services
└── ProviderInterface   # Common interface for database and web service
```

### Key Design Patterns

#### 1. **Readonly Properties for Immutable Data**
All model and record classes use PHP 8.1+ `readonly` properties for immutability and performance:

```php
class AnonymousPlus extends AnonymousIp
{
    public readonly ?int $anonymizerConfidence;
    public readonly ?string $networkLastSeen;
    public readonly ?string $providerName;
}
```

**Key Points:**
- Properties are set in the constructor and cannot be modified afterward
- Use `readonly` keyword for all public properties
- Nullable properties use `?Type` syntax
- Non-nullable booleans typically default to `false` in constructor logic

#### 2. **Inheritance Hierarchies**

Models follow clear inheritance patterns:
- `Country` → base model with country/continent data
- `City` extends `Country` → adds city, location, postal, subdivisions
- `Insights` extends `City` → adds additional web service fields
- `Enterprise` extends `City` → adds enterprise-specific fields

Records have similar patterns:
- `AbstractNamedRecord` → base with names/locales
- `AbstractPlaceRecord` extends `AbstractNamedRecord` → adds confidence, geonameId
- Specific records (`City`, `Country`, etc.) extend these abstracts

#### 3. **JsonSerializable Implementation**

All model and record classes implement `\JsonSerializable` for consistent JSON output:

```php
public function jsonSerialize(): ?array
{
    $js = parent::jsonSerialize();

    if ($this->anonymizerConfidence !== null) {
        $js['anonymizer_confidence'] = $this->anonymizerConfidence;
    }

    return $js;
}
```

- Only include non-null values in JSON output
- Use snake_case for JSON keys (matching API format)
- Properties use camelCase in PHP

#### 4. **Constructor Array Parameter Pattern**

Models and records are constructed from associative arrays (from JSON/DB):

```php
public function __construct(array $raw)
{
    parent::__construct($raw);
    $this->anonymizerConfidence = $raw['anonymizer_confidence'] ?? null;
    $this->networkLastSeen = $raw['network_last_seen'] ?? null;
}
```

- Use `$raw['snake_case_key'] ?? null` pattern for optional fields
- Use `$raw['snake_case_key'] ?? false` for boolean fields
- Call parent constructor first if extending another class

#### 5. **Web Service Only vs Database Models**

Some models are only used by web services and do **not** need MaxMind DB support:

**Web Service Only Models**:
- Models that are exclusive to web service responses
- Simpler implementation without database parsing logic
- Example: `Insights` (extends City but used only for web service)

**Database-Supported Models**:
- Models used by both web services and database files
- Must handle MaxMind DB format data structures
- Example: `City`, `Country`, `AnonymousIp`, `AnonymousPlus`

## Testing Conventions

### Running Tests

```bash
# Install dependencies
composer install

# Run all tests
vendor/bin/phpunit

# Run specific test class
vendor/bin/phpunit tests/GeoIp2/Test/Model/InsightsTest.php

# Run with coverage (if xdebug installed)
vendor/bin/phpunit --coverage-html coverage/
```

### Linting and Static Analysis

```bash
# PHP-CS-Fixer (code style)
vendor/bin/php-cs-fixer fix --verbose --diff --dry-run

# Apply fixes
vendor/bin/php-cs-fixer fix

# PHPCS (PSR-2 compliance)
vendor/bin/phpcs --standard=PSR2 src/

# PHPStan (static analysis)
vendor/bin/phpstan analyze

# Validate composer.json
composer validate
```

### Test Structure

Tests are organized by model/class:
- `tests/GeoIp2/Test/Database/` - Database reader tests
- `tests/GeoIp2/Test/Model/` - Response model tests
- `tests/GeoIp2/Test/WebService/` - Web service client tests

### Test Patterns

When adding new fields to models:
1. Update the test method to include the new field in the `$raw` array
2. Add assertions to verify the field is properly populated
3. Test both presence and absence of the field (null handling)
4. Verify JSON serialization includes the field correctly

Example:
```php
public function testFull(): void
{
    $raw = [
        'anonymizer_confidence' => 99,
        'network_last_seen' => '2025-04-14',
        'provider_name' => 'FooBar VPN',
        // ... other fields
    ];

    $model = new AnonymousPlus($raw);

    $this->assertSame(99, $model->anonymizerConfidence);
    $this->assertSame('2025-04-14', $model->networkLastSeen);
    $this->assertSame('FooBar VPN', $model->providerName);
}
```

## Working with This Codebase

### Adding New Fields to Existing Models

1. **Add the readonly property** with proper type hints and PHPDoc:
   ```php
   /**
    * @var int|null description of the field
    */
   public readonly ?int $fieldName;
   ```
2. **Update the constructor** to set the field from the raw array:
   ```php
   $this->fieldName = $raw['field_name'] ?? null;
   ```
3. **Update `jsonSerialize()`** to include the field:
   ```php
   if ($this->fieldName !== null) {
       $js['field_name'] = $this->fieldName;
   }
   ```
4. **Add comprehensive PHPDoc** describing the field, its source, and availability
5. **Update tests** to include the new field in test data and assertions
6. **Update CHANGELOG.md** with the change

### Adding New Models

When creating a new model class:

1. **Determine if web service only or database-supported**
2. **Follow the pattern** from existing similar models
3. **Extend the appropriate base class** (e.g., `Country`, `City`, or standalone)
4. **Use `readonly` properties** for all public fields
5. **Implement `\JsonSerializable`** interface
6. **Provide comprehensive PHPDoc** for all properties
7. **Add corresponding tests** with full coverage

### Deprecation Guidelines

When deprecating fields:

1. **Use `@deprecated` in PHPDoc** with version and alternative:
   ```php
   /**
    * @var bool This field is deprecated as of version 3.2.0.
    *           Use the anonymizer object from the Insights response instead.
    *
    * @deprecated since 3.2.0
    */
   public readonly bool $isAnonymous;
   ```
2. **Keep deprecated fields functional** - don't break existing code
3. **Update CHANGELOG.md** with deprecation notices
4. **Document alternatives** in the deprecation message

### CHANGELOG.md Format

Always update `CHANGELOG.md` for user-facing changes.

**Important**: Do not add a date to changelog entries until release time.

- If there's an existing version entry without a date (e.g., `3.3.0 (unreleased)`), add your changes there
- If creating a new version entry, use `(unreleased)` instead of a date
- The release date will be added when the version is actually released

```markdown
3.3.0 (unreleased)
------------------

* A new `fieldName` property has been added to `GeoIp2\Model\ModelName`.
  This field provides information about...
* The `oldField` property in `GeoIp2\Model\ModelName` has been deprecated.
  Please use `newField` instead.
```

## Common Pitfalls and Solutions

### Problem: Incorrect Property Types
Using wrong type hints can cause type errors or allow invalid data.

**Solution**: Follow these patterns:
- Optional values: `?Type` (e.g., `?int`, `?string`)
- Non-null booleans: `bool` (default to `false` in constructor if not present)
- Arrays: `array` with PHPDoc specifying structure (e.g., `@var array<string>`)

### Problem: Missing JSON Serialization
New fields not appearing in JSON output.

**Solution**: Always update `jsonSerialize()` to include new fields:
- Check if the value is not null before adding to array
- Use snake_case for JSON keys to match API format
- Call parent's `jsonSerialize()` first if extending

### Problem: Test Failures After Adding Fields
Tests fail because fixtures don't include new fields.

**Solution**: Update all related tests:
1. Add field to test `$raw` array
2. Add assertions for the new field
3. Test null case if field is optional
4. Verify JSON serialization

## Code Style Requirements

- **PSR-2 compliance** enforced by phpcs
- **PHP-CS-Fixer** rules defined in `.php-cs-fixer.php`
- **Strict types** (`declare(strict_types=1)`) in all files
- **Yoda style disabled** - use normal comparison order (`$var === $value`)
- **Strict comparison** required (`===` and `!==` instead of `==` and `!=`)
- **No trailing whitespace**
- **Unix line endings (LF)**

## Development Workflow

### Setup
```bash
composer install
```

### Before Committing
```bash
# Run all checks
vendor/bin/php-cs-fixer fix
vendor/bin/phpcs --standard=PSR2 src/
vendor/bin/phpstan analyze
vendor/bin/phpunit
```

### Version Requirements
- **PHP 8.1+** required
- Uses modern PHP features (readonly, union types, etc.)
- Target compatibility should match current supported PHP versions (8.1-8.4)

## Additional Resources

- [API Documentation](https://maxmind.github.io/GeoIP2-php/)
- [GeoIP2 Web Services Docs](https://dev.maxmind.com/geoip/docs/web-services)
- [MaxMind DB Format](https://maxmind.github.io/MaxMind-DB/)
- GitHub Issues: https://github.com/maxmind/GeoIP2-php/issues
