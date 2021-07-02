# ABC UMS

## Installation

Clone the repo locally:

```sh
git clone https://github.com/shalaka-deshan0110/abc-ums.git abc-ums
cd abc-ums
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm ci
```

Build assets:

```sh
npm run dev
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

Run the dev server (the output will give the address):

```sh
php artisan serve
```

You're ready to go! Visit ABC UMS in your browser, and login with:

- **Username:** super@admin.com
- **Password:** password

## Running tests

To run the ABC UMS tests, run:

```
phpunit
```
