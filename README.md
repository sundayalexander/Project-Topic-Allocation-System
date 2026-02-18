# Student-to-Project Topic Allocation (SPA) System

A Genetic Programming (GP) based system for optimizing the allocation of undergraduate project topics to students at
Adekunle Ajasin University.

## Overview

This project addresses the inefficiencies in manual project allocation by mismatching students' research interests with
supervisors' expertise. It uses a Genetic Programming-based model to optimize the allocation of project topics based on
research domains and student preferences.

The problem is formulated as a constrained optimization model incorporating:

- Supervisors' capacity and experience.
- Student preference matrices.
- Soft computing approach using Genetic Algorithms (Selection, Crossover, Mutation).

The system achieved an allocation optimality of 75.86% in evaluations.

## Key Features

- **Genetic Programming Engine**: Core logic for generating optimal allocations (found in `src/GP`).
- **Role-based Access**: Separate interfaces for Students, Supervisors, and Admins.
- **Constrained Optimization**: Handles supervisor capacity and student preferences.
- **Automated Allocation**: Replaces manual, error-prone methods.

## Tech Stack

- **Language**: PHP ^7.2.14
- **Framework**: Symfony 4.2.*
- **Database**: MySQL / Doctrine ORM
- **Package Manager**: Composer
- **Frontend**: Twig, Bootstrap, jQuery

## Project Structure

```text
├── bin/                # Symfony console executable
├── config/             # Configuration files (routes, services, packages)
├── public/             # Web root (assets, entry point index.php)
├── src/
│   ├── Controller/     # MVC Controllers (Admin, Student, Supervisor, Home)
│   ├── Entity/         # Doctrine Database Entities
│   ├── Form/           # Symfony Form Types
│   ├── GP/             # Core Genetic Programming Engine (DNA, Population, etc.)
│   ├── Migrations/     # Database migration scripts
│   └── Repository/     # Doctrine Repositories
├── templates/          # Twig templates
├── .env                # Environment variables
└── composer.json       # Project dependencies and scripts
```

## Requirements

- PHP ^7.2.14
- MySQL
- Composer
- PHP Extensions: `ctype`, `iconv`

## Installation & Setup

1. **Clone the repository**
2. **Install dependencies**
   ```bash
   composer install
   ```
3. **Configure Environment Variables**
    - Copy `.env` to `.env.local` (if needed) and update `DATABASE_URL`.
    - Default database name: `projallocator`
4. **Setup Database**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```
5. **Start the Development Server**
   ```bash
   php bin/console server:run
   ```

## Scripts

- `php bin/console`: Main entry point for Symfony commands.
- `composer install`: Install project dependencies.
- `php bin/console cache:clear`: Clear the application cache.
- `php bin/console assets:install`: Install web assets to the public directory.

## Environment Variables

- `APP_ENV`: Application environment (`dev`, `prod`, `test`).
- `APP_SECRET`: Symfony application secret.
- `DATABASE_URL`: Database connection string (e.g., `mysql://db_user:db_password@127.0.0.1:3306/db_name`).

## Testing

The project mentions unit, integration, and system testing in the abstract.

- TODO: Add specific instructions for running tests once a test suite is configured (no `tests/` directory was found in
  the root).

## Contributors

| S. A. Amowe                          | O.O. Ajayi                           |
|--------------------------------------|--------------------------------------|
| Computer Science Dept.               | Computer Science Dept.               |
| Adekunle Ajasin University           | Adekunle Ajasin University           |
| Akungba – Akoko, Ondo state, Nigeria | Akungba – Akoko, Ondo state, Nigeria |
| amowesunday@hotmail.com              | olusola.ajayi@aaua.edu.ng            |

## License

Proprietary (as per `composer.json`).




