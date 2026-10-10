# Laravel Request System - Lab 1

## Project Title
Laravel Request System

## Project Description
This project is a simple Laravel-based application that demonstrates the setup of a development environment, database connection, and GitHub workflow.

## Student Information
Name: Althea Mera M. Lascota. Michaela Pauline Deticio  
Course: BSIT  
Year & Section: 4-3 WMA  

## Software Requirements
- PHP
- Composer
- Laravel Framework
- MySQL
- Git
- GitHub

## Database Name
laravel_request_system_db

## Installation Steps
1. Clone repository
2. Run composer install
3. Copy .env.example to .env
4. Configure database
5. Run migration:
   php artisan migrate
6. Start server:
   php artisan serve

## How to Run the Project
http://127.0.0.1:8000

## Git Commands Used
git init  
git add .  
git commit -m "Initial Laravel project setup"  
git push -u origin main  

## GitHub Repository
https://github.com/mera2826/laravel-request-system


## Laboratory 2 – Request Data Model

### Database

Database Name: laravel_request_system_db

### Request Table

The requests table contains the following fields:

| Field | Data Type | Constraint | Purpose |
|---|---|---|---|
| id | BIGINT | Primary Key, Auto Increment | Unique request number |
| requester_name | VARCHAR(100) | Required | Person submitting the request |
| requester_email | VARCHAR(255) | Required | Contact address |
| item_name | VARCHAR(150) | Required | Requested item or service |
| quantity | UNSIGNED INTEGER | Required | Requested quantity |
| purpose | TEXT | Required | Reason for the request |
| status | VARCHAR(20) | Default: pending | Request state |
| created_at | TIMESTAMP | Timestamp | Creation time |
| updated_at | TIMESTAMP | Timestamp | Update time |

### Migration

The request table migration was created using:

```bash
php artisan make:migration create_requests_table
The migration was executed using:
php artisan migrate
The migration status was verified using:
php artisan migrate:status
Table Verification
The requests table was verified using phpMyAdmin. The table contains the required fields, including requester information, requested item, quantity, purpose, status, and timestamps.
Three fictional sample requests were inserted into the table. All sample requests have a positive quantity and a pending status.
One sample request was inserted without specifying the status field. The database automatically assigned pending as the status because pending is the default value defined in the migration.
User Stories
1. Requester
As a requester, I want to submit my name, email, requested item, quantity, and purpose so that my request contains all necessary information for processing.
Acceptance Criteria:
The request contains a requester name, email, item name, quantity, and purpose.
The quantity is greater than zero.
A new request has a default status of pending.
2. Staff Reviewer
As a staff reviewer, I want to view the request information and status so that I can review and process submitted requests.
Acceptance Criteria:
The staff reviewer can view the requester name, item name, quantity, purpose, and status.
A newly created request has a pending status.
3. Record Keeper
As a record keeper, I want each request to have a unique ID and timestamps so that requests can be identified and tracked over time.
Acceptance Criteria:
Each request has a unique ID.
Each request contains created_at and updated_at timestamps.
Quantity and Status Explanation
The request quantity must be greater than zero because a request should represent an actual amount being requested. Although an unsigned integer prevents negative values, it still permits zero. Therefore, the sample requests use positive quantities. Application-level validation for the positive-quantity rule will be implemented in a later laboratory.
A new request begins with pending status because it has been submitted but has not yet been reviewed or processed by staff.

## Laboratory 3 Verification

Verification instruction: Test administrator access and administrator-only status updates.
