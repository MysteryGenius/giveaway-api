# Getting Setup

## GiveawayAPI

### Considerations

The technical challenge states that we should anticipate high visitor volumes. Since each validation will take 10 minutes to complete, we will need to handle it as a background job. This might cause a race condition if we do not implement a locking mechanism. We will need to ensure that the same voucher code is not used twice.

### Dependencies

- [Commitlint](https://commitlint.js.org) - Lint commit messages

---

Install all Laravel dependencies

```bash
composer install
```

Install all Node dependencies

```bash
yarn
```

Create a copy of your .env file

```bash
copy .env.example .env
```

Create a copy of your .env file

```bash
php artisan key:generate
```

Setup the database. Only do this if you have the database installed. Else use the sail instructions.

```bash
php artisan migrate:fresh --seed
```

## WSL2 Setup

---

[Use this tutorial to install WSL2](https://docs.microsoft.com/en-us/windows/wsl/install-win10)

Aliasing the Sail command. First we nano into the file.

```bash
nano ~/.bashrc
```

Next we copy and paste in the alias definition.

```bash
alias sail='bash vendor/bin/sail'
```

## Laravel Sail

---

For ease of development we will be using docker to lock in all the dependencies. Using Docker with Windows 10 requires WSL2 and Docker Desktop.

Change directory in your WSL2 Distro to match the location.

Setup the database.

```bash
sail artisan migrate:fresh --seed
```

Setup the development server.

```bash
sail up
```

Share the development server on a public URL.

```bash
sail share
```
