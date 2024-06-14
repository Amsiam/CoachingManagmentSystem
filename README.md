## Getting started

### Launch the starter project

_(Assuming you've [installed Laravel](https://laravel.com/docs/11.x/installation))_

Fork this repository, then clone your fork, and run this in your newly created directory:

```bash
composer install
```

Next you need to make a copy of the `.env.example` file and rename it to `.env` inside your project root.

Run the following command to generate your app key:

```
php artisan key:generate
```

Update database credentials and 

```
php artisan migrate --seed
```

Then start your server:

```
php artisan serve
```

Admin email : admin@admin.com
Admin password: password
