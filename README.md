# MovieBuff-monolith
Laravel 5.6 monolith implementation of https://github.com/breadthe/moviebuff

*Migrated from Gitlab*

# Installation

These instructions assume the use of Homestead. For other types of Vagrant boxes or Docker, feel free to create your own instructions and PR them here. Also assumes you have the necessary tools installed and in your path (`git`, `php`, `composer`, `npm`, `yarn`, `artisan`).

## Mac

Install [Homestead](https://laravel.com/docs/5.6/homestead#installation-and-setup).

Configure `Homestead.yaml` as follows (relevant bits shown only):

```yaml
folders:
    - map: ~/source/laravel/moviebuff-monolith
      to: /home/vagrant/code

sites:
    - map: moviebuff.test
      to: /home/vagrant/code/public

databases:
    - moviebuff
```

Add an entry to your `hosts` file (`/etc/hosts`):

```bash
192.168.10.10   moviebuff.test
```

Clone the repo (creates a `moviebuff-monolith` directory).

```bash
git clone https://github.com/breadthe/moviebuff-monolith.git
```

Run `composer` and `npm`/`yarn` (I prefer `yarn`) to install PHP/JS packages:

```bash
cd moviebuff-monolith
composer install
npm install
```

Install Passport:

```bash
php artisan passport:install
```

Alternately:

```bash
php artisan passport:client --password
```

Next, configure Laravel. Generate a `.env`:

```bash
cp .env.example .env
```

I use these values (relevant bits shown only):

```
APP_NAME=MovieBuff
APP_URL=http://moviebuff.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=moviebuff
DB_USERNAME=homestead
DB_PASSWORD=secret

OMDB_API_KEY={YOUR OMDB API KEY}
GOOGLE_PLACES_API_KEY={YOUR GOOGLE PLACES API KEY}
```

Run database migrations:

```bash
php artisan migrate
```

One additional thing that I like to do is to allow Artisan to run on the local DB port (3306) instead of the Homestead (33060) port. So in your `.bashrc` or `.zshrc` file, export the local DB port:

```bash
export DB_PORT=33060
```

Finally, switch back to your Homestead directory and run:

```bash
vagrant up
```

The site should now be accessible in the browser at `moviebuff.test`.

**NOTE:** If I have missed any steps, feel free to correct me with a PR.

## Windows

You're on your own, buddy! Again, feel free to make a PR if you have Windows-specific instructions.

## Linux

See above.
