# Hirable REST API

API for Hirable app.

## Sanctum authentication

Because this API uses Sanctum for authentication, the API and frontend must share the same
top-level domain.

Frontend should also send `Accept` header with `application/json` value along with either `Referer`
or `Origin` header set.

More info [here](https://laravel.com/docs/10.x/sanctum#spa-authentication).

## Application setup

Before running any command, make sure you are in the project folder.

Firstly, copy [.env file](./.env.example) and set needed environment variables.

```bash
cp .env.example .env
```

(optional) To have functional email sending, set SMTP variables. You can use [Mailtrap](https://mailtrap.io/) or **Mailpit** as email client.

- `MAIL_MAILER`
- `MAIL_HOST`
- `MAIL_PORT`
- `MAIL_USERNAME`
- `MAIL_PASSWORD`
- `MAIL_ENCRYPTION`

Install Composer dependencies using Docker container.

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

Set Laravel Sail alias in your `~/.zshrc` or `~/.bashrc`.

```text
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

Start Sail.

```bash
sail up
```

All containers should start. If MySQL container can't successfully boot, try to use these commands.

```bash
docker-compose down --volumes

sail up --build
```

Now generate application key for encryption. This command should set `APP_KEY` env variable.

```bash
sail art key:generate
```

Lastly, run application installation command.

```bash
sail art app:install
```

## IDE setup

You should exclude listed folders from indexing for better performance if your IDE allows it.

- `bootstrap/cache`
- `docs`
- `public`
- `resources`
- `storage`
- `stubs`
- `vendor`

## Commands

Refactor code (dry-run)

```bash
sail composer refactor:dry
```

Refactor code

```bash
sail composer refactor
```

Reformat code

```bash
sail composer format
```

Reformat & refactor code

```bash
sail composer format:all
```

Run all tests

```bash
sail composer test
```

Check code format

```bash
sail composer test:lint
```

Check type coverage

```bash
sail composer test:type-coverage
```

Check coverage

```bash
sail composer test:coverage
```

Analyse code

```bash
sail composer test:analyse
```

Run all test commands at once

```bash
sail composer test:all
```

## Notifications preview

You can preview all notifications that are sent from the API. The preview is available
at `/notifications-preview`.

The preview is available only on `debug` and `local` environments.

## Coding standards

[Here](docs/standards/index.md).
