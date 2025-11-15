# Laravel TOON Export

A Laravel package that lets you export data (Eloquent models, queries, collections) into **TOON format** – optimized for LLM/token usage.

Think of it as: **`Export::toJSON` / `toCSV` → now also `toTOON`**

## Installation

### From Packagist (Recommended - Public Repo)

If the package is published on Packagist:

```bash
composer require azzoelabbar/laravel-toon-export
```

### From Private GitHub Repository

If the repository is **private**, you need to configure Composer authentication:

#### 1. Create GitHub Personal Access Token

1. Go to https://github.com/settings/tokens
2. Generate new token (classic) with `repo` scope
3. Copy the token

#### 2. Configure Composer

```bash
composer config --global github-oauth.github.com YOUR_GITHUB_TOKEN
```

#### 3. Add VCS Repository

In your `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/azzoelabbar/laravel-toon-export"
    }
  ],
  "require": {
    "azzoelabbar/laravel-toon-export": "@dev"
  }
}
```

#### 4. Install

```bash
composer require azzoelabbar/laravel-toon-export:@dev
```

### For Local Development

If you're developing the package locally, you can use a path repository:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "./packages/egate/laravel-toon-export"
    }
  ],
  "require": {
    "azzoelabbar/laravel-toon-export": "@dev"
  }
}
```

Then run:
```bash
composer update azzoelabbar/laravel-toon-export
```

The package will auto-discover and register itself.

## Why TOON?

**Instead of bloated JSON:**
```json
[
  { "id": 1, "name": "Panadol", "price": 3.5 },
  { "id": 2, "name": "Nizoral", "price": 18.9 }
]
```

**You get compact TOON:**
```
products[2]{id,name,price}:
1,Panadol,3.5
2,Nizoral,18.9
```

That's **easier on tokens** and still structured – perfect for AI pipelines!

## Quick Start

```php
use Egate\ToonExport\Facades\ToonExport;
use App\Models\User;

// Export from collection
$users = User::select('id', 'name', 'email')->get();
$toon = ToonExport::fromCollection($users, 'users', ['id', 'name', 'email']);

// Export from query
$toon = ToonExport::fromQuery(
    User::query()->where('active', true),
    'active_users',
    ['id', 'name', 'email']
);

// Output:
// users[3]{id,name,email}:
// 1,Azzo,azzo@example.com
// 2,Ali,ali@example.com
// 3,Sara,sara@example.com
```

## Usage

### Export from Collection

```php
$products = Product::all();
$toon = ToonExport::fromCollection($products, 'products', ['id', 'name', 'price']);
```

### Export from Query

```php
$toon = ToonExport::fromQuery(
    Order::query()->where('status', 'completed'),
    'orders',
    ['id', 'user_id', 'total']
);
```

### HTTP Response

```php
Route::get('/export/users.toon', function () {
    $users = User::select('id', 'name', 'email')->get();
    $toon = ToonExport::fromCollection($users, 'users', ['id', 'name', 'email']);
    
    return response($toon, 200, [
        'Content-Type' => 'text/plain; charset=utf-8',
        'Content-Disposition' => 'attachment; filename="users.toon"',
    ]);
});
```

### Artisan Command

Export models directly from the command line:

```bash
# Export all users with selected columns
php artisan toon:export "App\Models\User" \
  --name=users \
  --columns=id,name,email

# Output: storage/app/toon/users.toon
```

**Command Options:**
- `model` (required): Fully qualified model class, e.g. `App\Models\User`
- `--name`: Root TOON name (defaults to snake_case of model name)
- `--columns`: Comma-separated list of columns to export
- `--path`: Subdirectory under `storage/app` (default: `toon`)

**Examples:**

```bash
# Export with default name (uses model name)
php artisan toon:export "App\Models\Product"
# Creates: storage/app/toon/product.toon

# Export with custom name and columns
php artisan toon:export "App\Models\User" \
  --name=active_users \
  --columns=id,name,email,role

# Export to custom directory
php artisan toon:export "App\Models\Order" \
  --name=recent_orders \
  --path=exports/toon
# Creates: storage/app/exports/toon/recent_orders.toon
```

## TOON Format

The TOON format follows this structure:

```
table_name[record_count]{column1,column2,column3}:
value1,value2,value3
value1,value2,value3
...
```

**Example:**
```
products[3]{id,name,price,stock}:
1,Panadol,3.5,100
2,Nizoral,18.9,50
3,Aspirin,2.0,
```

## Requirements

- PHP ^8.1
- Laravel ^10.0 || ^11.0 || ^12.0

## License

MIT
