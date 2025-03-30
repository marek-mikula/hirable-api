# Testing

## Folder structure

Folder structure should follow the same structure as tested code.

Each test should always have at least **two** groups based on the folder structure.

### Domain tests

For instance: `src/Domain/Auth/Http/Controllers/AuthController`

- `tests/Feature/Domain/Auth/Http/Controllers/AuthControllerTest`
- `tests/Unit/Domain/Auth/Http/Controllers/AuthControllerTest`

Tests should have these groups:

- `domain`
- `domain-{domainName}`

### Support tests

For instance: `src/Support/Token/Http/Middleware/TokenMiddleware`

- `tests/Feature/Support/Token/Http/Middleware/TokenMiddlewareTest`
- `tests/Unit/Support/Token/Http/Middleware/TokenMiddlewareTest`

Tests should have these groups:

- `support`
- `support-{packageName}`

### App folder tests

For instance: `app/Services/Formatter`

- `tests/Feature/App/Services/FotmatterTest`
- `tests/Unit/App/Services/FotmatterTest`

Tests should have these groups:

- `app`

## Writing tests

### External HTTP requests

During testing all external HTTP requests should be mocked!

### Unit

Units that need to be fully covered with tests:

- repositories
- mutators and accessors in eloquent models
- middlewares (without default laravel middlewares if no changes were made)
- form request classes (only validation rules)
- invokable validation classes (used in after method)
- jobs
- schedules

Every unit test should test **only one class**. Every dependency
should be mocked.

Some unit tests does not have to mock all dependencies. These classes include:
- jobs
- schedules

### Feature

Features that need to be fully covered with tests:

- controllers
  - authorization
  - response codes
  - route model binding
- service classes

**Do not test request validation in controller tests! It is tested in the unit tests.**

### Process

Process testing includes comprehensive processes.
- authentication
- registration
- password reset...

