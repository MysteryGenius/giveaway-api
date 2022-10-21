# GiveawayAPI

Simple API for giveaways. The API available allows you to create the giveaway page. The authentication is done via a bearer token. The Availability API allows the user to check if there are any vouchers available. Other endpoints available are the Index and Show for Vouchers and Customer.

The two core API endpoints are the Eligibility and Submission endpoints. The Eligibility endpoint allows the user to check if they are eligible for the giveaway. The Submission endpoint allows the user to submit their details and receive a voucher.

The Eligibility endpoint locks down a voucher if the user is eligible. There will only be 10 minutes to submit the details before the voucher is unlocked and available for another user. This is achieved by using a DB Query. Ideally this would be done using a Redis cache.

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

#### Others

```

GET /api/vouchers
GET /api/vouchers/:voucher_code

GET /api/customers
GET /api/customers/:id

```


## Considerations

The technical challenge states that we should anticipate high visitor volumes. Since each validation will take 10 minutes to complete, we will need to handle it as a background job. This might cause a race condition if we do not implement a locking mechanism. We will need to ensure that the same voucher code is not used twice.

## Dependencies

### Javascript

- [Commitlint](https://commitlint.js.org) - Lint commit messages

### PHP

- [Pest](https://pestphp.com) - Testing framework
- [Laravel](https://laravel.com) - PHP framework
- [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum) - Authentication

### Testing

- [Postman](https://www.postman.com) - API testing

## Installation

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

## Deployment

The application would be best deployed on Digital Ocean using Laravel Forge to provision the services. Since likely this is a one time service. We can use a single droplet to host the application. The droplet would need to have the following installed. However if we are going to scale this application we can use vapor to host the application on AWS.
