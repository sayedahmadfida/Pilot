# Laravel CRUD Generator

Laravel CRUD Generator is a simple package that helps you quickly generate a complete CRUD system in your Laravel project with AdminLTE v4 UI.

The package automatically creates all necessary backend and frontend files including controllers, models, migrations, requests, views, JavaScript, and routes.

---

## Installation

Install the package using Composer:

```bash
composer require fida/laravel-crud-generator
```

---

## Setup Admin Template

After installing the package, run the following command:

```bash
php artisan pilot:config
```

This command will install and configure the **AdminLTE v4** template inside your Laravel project.

---

## Generate CRUD

To generate a complete CRUD module, run:

```bash
php artisan pilot:crud ModelName
```

### Example

```bash
php artisan pilot:crud Product
```

---

## What This Command Generates

The `pilot:crud` command will automatically generate:

- Model
- Migration
- Controller
- Form Request (Validation)
- Views
  - Index Page
  - Create Form (Modal)
  - Edit Form (Modal)
  - Table View
- JavaScript File
  - Form validation
  - AJAX requests
  - Table rendering
- Routes
- Sidebar menu item

---

## Features

- Full CRUD functionality
- AJAX based forms
- Dynamic table rendering
- Validation using Laravel Form Requests
- AdminLTE v4 UI integration
- Automatic sidebar menu generation
- Clean JavaScript structure

---

## Workflow

1. Install the package

```bash
composer require fida/laravel-crud-generator
```

2. Configure the admin template

```bash
php artisan pilot:config
```

3. Generate CRUD module

```bash
php artisan pilot:crud ModelName
```

Your CRUD module will be ready to use.

---

## Requirements

- PHP 8+
- Laravel 10+

---

## License

This package is open-source and available under the MIT License.
