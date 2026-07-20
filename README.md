
# SPARISK

## Run Locally

Clone the project

```bash
  git clone https://github.com/faizulMyori/laravel-template-react.git
```

Go to the project directory

```bash
  cd SPARISK
```

Install dependencies

```bash
  npm install
  composer install
```

Copy .env and generate key

```bash
  cp .env.example .env
  php artisan key:generate
```

Migrate Databases

```bash
  php artisan migrate
```

Seed Databases

```bash
  php artisan db:seed RolePermissionSeeder
```

Init Storage

```bash
  php artisan storage:link
```

Dump and clear cache

```bash
  php artisan optimize:clear
```

Start new vite server

```bash
  npm run dev
```

Start new php server

```bash
  php artisan serve
```
"# sparisk" 
"# sparisk" 
"# sparisk" 
"# sparisk" 
"# sparisk" 
"# sparisk" 
