# GiveawayAPI

## Endpoints

#### Login

```
POST /api/auth/login
```

<img src="https://user-images.githubusercontent.com/38975808/197118860-1a4b2c81-34ba-47fa-99eb-5b3cab904d07.png" width="500" />

This endpoint allows you to login to an authorized account to access resicted content. You will need to set the `X-XSRF-TOKEN` to prevent a CSRF issue. After POSTing to the endpoint you will have a bearer token to be used as the auth key in the end points below

#### Availability

```
GET /api/campaign/availability
```

<img src="https://user-images.githubusercontent.com/38975808/197118982-0afa53ed-2b29-4d17-91ce-f946b030ec0f.png" width="500" />

This endpoint returns the number of availabile unclaimed vouchers

#### Eligibility

```
POST /api/campaign/eligibility

body 
{
    "customer_id": "1"
}
```

<img src="https://user-images.githubusercontent.com/38975808/197119042-c0314425-2dea-4aa1-a07c-1efcc717a16b.png" width="500" />

This endpoint checks if the customer qualifies for the event

#### Submission

```
POST /api/campaign/submission

body
{
    "customer_id: "1",
    "submission_image_path": "example.com"
}
```

<img src="https://user-images.githubusercontent.com/38975808/197119292-aca5b6c9-3c3d-41d0-9d29-1ba79295122d.png" width="500" />

This is to submit the validation photo within ten minutes.

#### Upload Image

```
POST /api/upload-photo-submission
```


## Considerations

The technical challenge states that we should anticipate high visitor volumes. Since each validation will take 10 minutes to complete, we will need to handle it as a background job. This might cause a race condition if we do not implement a locking mechanism. We will need to ensure that the same voucher code is not used twice.

## Dependencies

### Javascript

- [Commitlint](https://commitlint.js.org) - Lint commit messages

### PHP

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
