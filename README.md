## Laravel E-Commerce Site

<img src="https://travis-ci.com/zaknesler/cart-api.svg?token=Q3xfxX8b5n9HsoYBpqri&branch=master" alt="Travis CI" />

A Laravel shopping site, built using the awesome Codecourse [e-commerce series](https://codecourse.com/watch/build-an-e-commerce-platform).

### Installation

Clone the repository

```bash
git clone git@github.com:zaknesler/cart-api
```

Install Composer dependencies and generate environment secrets

```bash
cd cart-api
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

Then set up and migrate the database with seeds (SQLite does not work with this codebase)

```bash
php artisan migrate --seed
```

Lastly, configure the Stripe [API keys](https://dashboard.stripe.com/account/apikeys) in the `.env` file

### Testing

Run the phpunit binary to run the tests

```bash
./vendor/bin/phpunit
```
