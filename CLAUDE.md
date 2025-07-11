# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is **laravel-saga**, a Laravel package that helps manage complex workflows by breaking them down into sequential steps. It orchestrates these steps as a 'saga', executing them in order and tracking progress in the database. The package is designed for long-running processes like e-commerce order flows or video processing pipelines.

## Development Commands

### Testing
```bash
composer test                 # Run all tests using Pest
vendor/bin/pest              # Direct Pest execution
composer test-coverage       # Run tests with coverage report
```

### Code Quality
```bash
composer analyse             # Run PHPStan static analysis
composer format              # Format code using Laravel Pint
vendor/bin/pint              # Direct Pint execution
vendor/bin/phpstan analyse   # Direct PHPStan execution
```

### Package Development
```bash
composer run prepare         # Discover package for Testbench
php artisan make:saga-step   # Generate new saga step class
```

### Installation Testing
```bash
php artisan saga:install     # Install package (publishes config, runs migrations)
php artisan migrate          # Run migrations for saga tables
```

## Architecture

### Core Components

**Saga Class** (`src/Saga.php`): The main orchestrator that chains steps and dispatches them using Laravel's Bus system. Uses named constructor pattern with fluent interface.

**SagaStep Abstract Class** (`src/SagaStep.php`): Base class for all saga steps. Implements `ShouldQueue` and handles status tracking, error management, and context sharing between steps.

**SagaContext** (`src/SagaContext.php`): Manages shared data between steps with persistent storage. Provides get/set/merge operations that automatically persist to database.

**Models**: 
- `SagaRun` - Tracks the overall saga execution
- `SagaStep` - Tracks individual step execution and status

### Key Patterns

1. **Job Chaining**: Uses Laravel's `Bus::chain()` to execute steps sequentially
2. **Status Tracking**: Each step tracks status (pending → started → completed/failed) in database
3. **Context Sharing**: Steps can share data via `$this->context()` which persists automatically
4. **Error Handling**: Failed steps are caught, logged, and halt the chain
5. **Testing Support**: Includes `SagaFake` for testing without actual job dispatch

### Package Structure

- **Commands**: Artisan commands for generating steps and package management
- **Facades**: Laravel facade for easy access (`Saga::named()`)
- **Migrations**: Database tables for saga_runs and saga_steps
- **Service Provider**: Auto-discovery, command registration, singleton binding

### Testing Framework

Uses **Pest PHP** with Orchestra Testbench for Laravel package testing. Test environment uses in-memory SQLite and automatically runs migrations. The `SagaFake` class provides testing assertions:

- `Saga::assertDispatched(name, steps?)`
- `Saga::assertNotDispatched(name)` 
- `Saga::assertDispatchedCount(count)`

### Dependencies

- Laravel 10/11/12 support
- PHP 8.2+ requirement
- Spatie Laravel Package Tools for service provider
- Queue system dependency for step execution