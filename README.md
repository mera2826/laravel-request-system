# Laravel Request System

## Project Overview

The Laravel Request System is a web application developed for the laboratory activities. It demonstrates Laravel development, request data management, authentication, request ownership, administrator permissions, Docker containerization, database persistence, and a GitHub workflow.

## Student Information

- **Students:** Althea Mera M. Lascota and Michaela Pauline Deticio
- **Course:** BSIT
- **Year and Section:** 4-3 WMA

## Technologies Used

- Laravel Framework 12.69.2
- PHP 8.3 FPM Docker image
- MySQL 8.4
- Nginx Alpine
- Docker and Docker Compose
- Composer
- Git and GitHub

## GitHub Repository

https://github.com/mera2826/laravel-request-system

## Laboratory 1: Project Setup and Git Workflow

The initial laboratory established the Laravel project, database connection, and GitHub repository.

### Traditional Development Setup

1. Clone the repository.
2. Install dependencies using `composer install`.
3. Configure the application's environment variables.
4. Configure the database connection.
5. Run database migrations using `php artisan migrate`.
6. Start the development server using `php artisan serve`.

The traditional local development URL is `http://127.0.0.1:8000`.

## Laboratory 2: Request Data Model

The `requests` table stores information about submitted requests.

| Field | Description |
|---|---|
| `id` | Unique request identifier |
| `requester_name` | Requester's name |
| `requester_email` | Requester's email address |
| `item_name` | Requested item or service |
| `quantity` | Requested quantity |
| `purpose` | Reason for the request |
| `status` | Current request status |
| `user_id` | User associated with the request |
| `created_at` | Record creation timestamp |
| `updated_at` | Record update timestamp |

The project includes migrations for users, requests, administrator status, and request ownership.

## Laboratory 3: Authentication and Request Ownership

The application includes authentication and authorization controls.

- Regular users can submit requests and access their own requests.
- Regular users must not access another user's private request.
- Administrators can view requests across users.
- Request status changes are restricted to administrators.
- Request validation helps prevent invalid submissions.

These permissions should be verified using separate regular-user accounts and an administrator account.

## Laboratory 4: Docker Containerization

### Objectives

- Run the Laravel application in a reproducible Docker environment.
- Separate the web server, application runtime, and database into services.
- Persist database records across container recreation.
- Keep database credentials outside version control.
- Restrict published ports and run the application as a non-root user.
- Document setup, verification, and reviewer reproduction procedures.

### Architecture

```text
Browser
   |
   | http://127.0.0.1:8080
   v
Nginx Web Container
   |
   | FastCGI: app:9000
   v
Laravel Application Container
   |
   | Internal Docker network
   v
MySQL 8.4 Database Container
   |
   v
Named Volume: db_data
```

### Docker Services

| Service | Image or build | Purpose |
|---|---|---|
| `web` | `nginx:alpine` | Serves public web requests |
| `app` | Built from `Dockerfile` | Runs Laravel using PHP-FPM |
| `db` | `mysql:8.4` | Stores application data |

The services communicate through a Docker bridge network named `app-network`.

The database uses the named volume `db_data` to preserve records when containers are removed and recreated.

### Prerequisites

Install Docker Desktop and Git. Ensure Docker Desktop is running before executing the commands below.

### Environment Configuration

The container setup uses `.env.container` for local configuration and secrets.

1. Create `.env.container` from `.env.container.example` if it does not already exist.
2. Set strong, private database credentials.
3. Configure the Laravel application key and database connection.
4. Keep `.env.container` out of Git commits.
5. Never publish actual passwords or secret keys in documentation or screenshots.

Do not replace a working environment file unnecessarily because it may contain the application's existing key and credentials.

### Build and Start the Containers

From the project directory, run:

```bash
docker compose --env-file .env.container config --quiet
docker compose --env-file .env.container up -d --build
docker compose --env-file .env.container ps
```

Open the application at:

http://127.0.0.1:8080

### Database Migrations

Check the migration status:

```bash
docker compose --env-file .env.container exec app php artisan migrate:status
```

If migrations are pending, apply them using:

```bash
docker compose --env-file .env.container exec app php artisan migrate
```

Do not use `migrate:fresh` on a database containing records that must be preserved.

### Verification Commands

Check the Laravel version:

```bash
docker compose --env-file .env.container exec app php artisan --version
```

Check the running services:

```bash
docker compose --env-file .env.container ps
```

Check the application container user:

```bash
docker compose --env-file .env.container exec app id
```

Check the configured named volumes:

```bash
docker compose --env-file .env.container config --volumes
```

### Persistence Verification

The following tests were performed in the development environment:

1. Verified that an existing request was present in MySQL.
2. Recreated the Laravel application container.
3. Verified that the same request remained available.
4. Ran `docker compose down`.
5. Started the environment again using `docker compose up -d --build`.
6. Verified that the database and existing request were still available.

The test request was ID `1`, with item name `Notebook`, status `approved`, and user ID `2`.

The database volume must not be deleted during persistence testing. Do not use `docker compose down -v`.

### Security Checks

The following checks were verified:

- The Nginx document root points to `/var/www/html/public`.
- The Laravel application container runs as the `www-data` user.
- The Nginx service publishes port 80 on the host loopback address at `127.0.0.1:8080`.
- The application port 9000 is not published on a host port.
- The MySQL ports are not published on host ports.
- The MySQL health check reports a healthy status.
- `.dockerignore` excludes environment files, dependencies, temporary files, backups, and private-key files.
- The database uses the persistent named volume `db_data`.

These checks do not replace a complete vulnerability scan or authorization test.

### Reviewer Reproduction Procedure

After the repository changes are reviewed and available to the reviewer:

1. Clone the repository into a separate project directory.
2. Check out the reviewed branch or commit.
3. Install Docker Desktop and start Docker.
4. Create a private `.env.container` based on `.env.container.example`.
5. Configure unique local credentials and a valid application key.
6. Build and start the services.
7. Check the service health and migration status.
8. Test authentication, request ownership, and administrator permissions.
9. Create a test request and record its ID.
10. Recreate the application container and verify that the request persists.
11. Record the commands, results, and any problems found.

The reviewer should use an isolated environment and must not use the original development database volume.

### Version and Git Evidence

Record the versions and starting commit used for the activity.

Commands:

```bash
docker --version
docker compose version
git log --oneline --reverse
git status
```

The Laravel version verified in the application container is `12.69.2`. Docker, Compose, and starting-commit details should be recorded from the actual environment and Git history.

### GitHub Pull Request

The Lab 4 changes are submitted through a pull request from `feature/lab4-containers` to `main`.

Pull request: Add the actual GitHub pull request URL here after creating it.

Record the reviewer, review result, approval, merge commit, and final status after those actions actually occur.

## Troubleshooting

### Containers are not running

```bash
docker compose --env-file .env.container ps
docker compose --env-file .env.container logs
```

### Configuration validation fails

```bash
docker compose --env-file .env.container config
```

Check the environment variables and Compose configuration for errors. Do not share private credentials in screenshots or public issues.

### Database records appear missing

Confirm that the correct Compose project and named volume are being used. Avoid deleting volumes or resetting the database while investigating.

## Conclusion

The laboratory project combines Laravel request management with Docker-based deployment practices. Verification evidence should document the running services, migration status, access controls, database persistence, security configuration, and GitHub review workflow. Any outstanding review, merge, or reproduction tasks must be recorded accurately.