# MovieBuff-monolith
Laravel 5.6 monolith implementation of https://github.com/breadthe/moviebuff

*Migrated from Gitlab*

# Installation

These instructions assume the use of Homestead. For other types of Vagrant boxes or Docker, feel free to create your own instructions and PR them here.

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

