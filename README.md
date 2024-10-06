# Blog Api

## CRUD Blog Api

A Blog Api created for the Courierplus - Backend Developer Assessment.

## Installation

Step-by-step instructions to set up the project locally:

1. Clone the repository:

    ```bash
    git clone https://github.com/pablo-codes/blog-api.git
    cd blog-api

    ```

2. Install dependencies:

```bash

composer install
```

3. Generate an application key

```bash

php artisan key:generate
```

4. Run the migrations

```bash

php artisan migrate
```

5. Seed the database (i.e Data is needed in the table for some operations)

```bash

php artisan db:seed
```

6. Serve the application:

```bash

php artisan serve
```

or TEST

```bash

php artisan test
```

## Usage

-   Click on the example in the post man request.
-   Click on try.
-   View Documentation to see expected responses for success and error.

## Postman

-   The Json file.

NB:Errors are logged in blog_api_error.log while info in blog_api.log
