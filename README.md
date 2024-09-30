# Todo App

This project is a Laravel-based application that handles task and project management with authentication. It includes features such as task filtering, project statistics, and user authentication.

## Installation

Copy the `.env` file:
```bash
cp .env.example .env
```
### With Docker
```
docker-compose down --volumes
docker-compose up --build -d
```
Your application should now be running at http://localhost


### Prerequisites

- PHP 8.3 or higher
- Composer
- MySQL or PostgreSQL database (adjust database driver accordingly)


### Steps

1. Clone the repository:

    ```bash
    git clone https://github.com/giorgigigauri/todolist-assignment.git
    cd todolist-assignment
    ```

2. Install the PHP dependencies:

    ```bash
    composer install
    ```

4. Generate Key and Run migrations and seeders :

    ```bash
    php artisan key:generate
    php artisan migrate --seed
    ```

7. Serve the application:

    ```bash
    php artisan serve
    ```

Your application should now be running at `http://localhost:8000`.

## Running Tests

This project uses **Pest** for testing. To run the tests, use the following command:

```bash
php artisan test
```


## POSTMAN
### Postman Documentation Link:
https://documenter.getpostman.com/view/5682430/2sAXqzVy2n#intro

you need credentials to auth, after accessing auth endpoint from postman, it will automatically set sanctum bearer token variable

### creadentials
```json
{
  "email": "admin@tasks.com",
  "password": "adminPass123"
}
```
## API Endpoints

### Authentication

- **POST** `/auth`

### Projects

- **GET** `/projects` – List all projects with pagination.
- **POST** `/projects` – Create a new project.
- **GET** `/projects/{id}` – Show details of a specific project.
- **PUT** `/projects/{id}` – Update an existing project.
- **DELETE** `/projects/{id}` – Delete a project.

### Tasks

- **GET** `/tasks` – List all tasks with filters and pagination.
- **POST** `/tasks` – Create a new task.
- **GET** `/tasks/{id}` – Show details of a specific task.
- **PUT** `/tasks/{id}` – Update an existing task.
- **DELETE** `/tasks/{id}` – Delete a task.

### Statistics

- **GET** `/statistics` – Get project task statistics (percentage of tasks completed per project).


## Features

- **User Authentication**: Secure authentication via API token generation.
- **Project Management**: Create, update, delete, and list projects.
- **Task Management**: Manage tasks within projects, with filtering based on status, due dates, and custom searches.
- **Statistics**: View project statistics, including task completion rates.
- **Search Functionality**: Search tasks and projects using advanced filters.
- **Pagination**: All lists are paginated for better performance.

## Architectural Overview

- **Modular Design**: Logic is split into Controllers, Actions, and Jobs to ensure separation of concerns, making the app more maintainable and testable.

- **RESTful API**: Follows a RESTful design for managing projects and tasks, providing consistent and easy-to-use endpoints.

- **Task Management**: Eloquent relationships manage links between tasks, projects, and users, while Spatie Query Builder allows flexible search and filtering.

- **Background Jobs**: Tasks like sending overdue reminders are handled by Laravel Jobs, ensuring asynchronous processing without blocking the main application.

- **Test-Driven Development**: The project uses Pest for comprehensive testing, covering CRUD operations and background jobs.

- **Authentication**: Laravel Sanctum is used for secure, token-based API authentication.
