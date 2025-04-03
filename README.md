# Group Expense API

## Introduction

This is an API that makes use of modular architecture with the use of the repository design pattern for each module to achieve a clean, scalable, and maintainable code base. Its focus is on the design, thus it shouldn't be used for production. The API is built using Laravel and provides functionalities for managing groups and memebers in a group expense-sharing system, allowing users to create, join, and manage groups with different roles (Owner, Administrator, and Member).

## Installation

To set up and run this Laravel application, follow these steps:

### Prerequisites

Make sure that you have the following installed in your machine:

- PHP (^8.2)
- [Composer](https://getcomposer.org/)
- Docker (For ease of use)

### Steps to Install

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd <repository-folder>
   ```
2. Copy the environment file:
   ```bash
   cp .env.example .env
   ```
3. Install dependencies:
   ```bash
   composer install
   ```
4. Generating optimised autoload files:
   ```bash
   composer du
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Start the application using Laravel Sail:
   ```bash
   ./vendor/bin/sail up -d
   ```
7. Run migrations and seed the database:
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```
8. (Optional) If you want to seed the database with module data:
   ```bash
   ./vendor/bin/sail artisan module:seed
   ```
9. The application is now ready to use.

## Modular Architecture

This application follows a modular architecture using the [`nWidart/laravel-modules`](https://github.com/nWidart/laravel-modules) package. It consists of three main modules:

### 1. Group Module
- Handles requests such as:
  - Get Groups
  - Create Group
  - Update Group
  - Delete Group

### 2. Membership Module
- Manages group memberships with the following requests:
  - Join Group
  - Get Group Members
  - Promote Member
  - Demote Member
  - Leave Group

### 3. Notification Module
- Listens for the following events and creates notifications in the database:
  - Group Created
  - Member Joined
  - Member Promoted
  - Member Demoted
  - Member Left

## API Documentation

A detailed API documentation is available via Postman. You can access it here:
[Postman Collection Link](https://www.postman.com/interstellar-flare-319171/workspace/group-expense-api-documentation/folder/3529449-066d457c-02cf-4aac-98ce-f55f53b13ef1?action=share&creator=3529449&ctx=documentation).

You can use this Postman API documentation to interact with the API, but you might need to download Postman and run requests locally.

## Authentication

- Authentication is required for all requests.
- The application provides the following authentication endpoints:
  - Register
  - Login
  - Logout
- Upon registration or login, a bearer token is provided, which must be included in the headers for authenticated requests.
- If you use the provided Postman API, registering or logging in will automatically attach the token to requests.

## Tests

Each module has corresponding tests to confirm that functionalities are working as expected. You can also use these tests as reference when doing API calls.

## Questions?

For any questions or issues, feel free to reach out!
