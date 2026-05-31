<p align="center">
  <img src="hashedxlaravel.png" alt="Hashed × Laravel" width="900">
</p>

<h1 align="center">Laravel <span style="color: #1A1241;">TOON Export</span></h1>

<p align="center">
  A <strong>Hashed</strong> package that exports Laravel data (Eloquent models, queries, collections) into <strong>TOON format</strong> — optimized for LLM/token usage.
</p>

<p align="center">
  <a href="https://packagist.org/packages/azzoelabbar/laravel-toon-export"><img src="https://img.shields.io/packagist/v/azzoelabbar/laravel-toon-export.svg?style=flat-square" alt="Latest Version"></a>
  <a href="https://packagist.org/packages/azzoelabbar/laravel-toon-export"><img src="https://img.shields.io/packagist/dt/azzoelabbar/laravel-toon-export.svg?style=flat-square" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/azzoelabbar/laravel-toon-export"><img src="https://img.shields.io/packagist/l/azzoelabbar/laravel-toon-export.svg?style=flat-square" alt="License"></a>
</p>

Think of it as: **`Export::toJSON` / `toCSV` → now also `toTOON`**

## Why TOON?

TOON format is a compact, structured data format perfect for AI/LLM pipelines. It uses significantly fewer tokens than JSON while maintaining structure.

**JSON (bloated):**
```json
[
  { "id": 1, "name": "Panadol", "price": 3.5 },
  { "id": 2, "name": "Nizoral", "price": 18.9 }
]
```

**TOON (compact):**
```
products[2]{id,name,price}:
1,Panadol,3.5
2,Nizoral,18.9
```

**Result:** ~70% fewer tokens, same information.

## Installation

Install via Composer:

```bash
composer require azzoelabbar/laravel-toon-export
```

The package will auto-discover and register itself. No configuration needed.

## Quick Start

```php
use Hashed\ToonExport\Facades\ToonExport;
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
// 3,Salah,salah@elabbar.com
```

## Usage

### Export from Collection

```php
use Hashed\ToonExport\Facades\ToonExport;

$products = Product::all();
$toon = ToonExport::fromCollection($products, 'products', ['id', 'name', 'price']);
```

### Export from Query Builder

```php
$toon = ToonExport::fromQuery(
    Order::query()
        ->where('status', 'completed')
        ->where('created_at', '>=', now()->subMonth()),
    'recent_orders',
    ['id', 'user_id', 'total', 'created_at']
);
```

### HTTP Download Response

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
# Basic export
php artisan toon:export "App\Models\User" --columns=id,name,email

# Custom name and path
php artisan toon:export "App\Models\Product" \
  --name=products \
  --columns=id,name,price \
  --path=exports
```

**Command Options:**
- `model` (required): Fully qualified model class, e.g. `App\Models\User`
- `--name`: Root TOON name (defaults to snake_case of model name)
- `--columns`: Comma-separated list of columns to export
- `--path`: Subdirectory under `storage/app` (default: `toon`)

**Examples:**
```bash
# Export with default name
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

## Advanced Usage

### Multiple Tables

```php
$users = ToonExport::fromCollection($users, 'users', ['id', 'name']);
$orders = ToonExport::fromCollection($orders, 'orders', ['id', 'user_id', 'total']);

$combined = $users . "\n\n" . $orders;
```

### Custom Data Arrays

```php
$data = [
    ['id' => 1, 'name' => 'Item 1', 'price' => 10.5],
    ['id' => 2, 'name' => 'Item 2', 'price' => 20.0],
];

$toon = ToonExport::fromCollection(
    collect($data), 
    'items', 
    ['id', 'name', 'price']
);
```

### AI/LLM Integration

```php
use Hashed\ToonExport\Facades\ToonExport;

// Export data for LLM processing
$users = User::where('city', 'Benghazi')->get();
$toon = ToonExport::fromCollection($users, 'users', ['id', 'name', 'email', 'city']);

// Send to OpenAI/Anthropic
$prompt = "Analyze this user data:\n\n{$toon}\n\nWhat patterns do you see?";
// ... send to LLM API
```

## TOON Format Specification

The TOON format follows this structure:

```
table_name[record_count]{column1,column2,column3}:
value1,value2,value3
value1,value2,value3
...
```

**Rules:**
- Header: `name[count]{columns}:`
- Data rows: comma-separated values, one per line
- Values with commas/newlines are automatically quoted
- Empty values are represented as empty strings

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

## Features

- Export from Eloquent Collections
- Export from Query Builders
- Artisan command for CLI exports
- Auto-discovery (no manual registration)
- Token-efficient format for AI/LLM
- Works with any Eloquent model
- Supports custom columns selection
- Automatic escaping for special characters

## Use Cases

- **AI/LLM Pipelines:** Send structured data to OpenAI, Anthropic, etc.
- **Data Exports:** Create compact export files
- **API Responses:** Return token-efficient data formats
- **Admin Dashboards:** Export data for analysis
- **Batch Processing:** Prepare data for AI processing

## Examples

### Controller Example

```php
<?php

namespace App\Http\Controllers;

use Hashed\ToonExport\Facades\ToonExport;
use App\Models\Product;

class ExportController extends Controller
{
    public function products()
    {
        $products = Product::select('id', 'name', 'price', 'stock')->get();
        
        $toon = ToonExport::fromCollection($products, 'products', [
            'id', 'name', 'price', 'stock'
        ]);
        
        return response($toon, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="products.toon"',
        ]);
    }
}
```

### Service Example

```php
<?php

namespace App\Services;

use Hashed\ToonExport\Facades\ToonExport;
use App\Models\User;

class AIDataService
{
    public function prepareUserDataForLLM(array $userIds): string
    {
        $users = User::whereIn('id', $userIds)
            ->select('id', 'name', 'email', 'created_at')
            ->get();
        
        return ToonExport::fromCollection($users, 'users', [
            'id', 'name', 'email', 'created_at'
        ]);
    }
}
```

## Testing

Test the package with:

```bash
# Test Artisan command
php artisan toon:export "App\Models\User" --columns=id,name,email

# Test in Tinker
php artisan tinker
```

Then:
```php
use Hashed\ToonExport\Facades\ToonExport;
use App\Models\User;

$users = User::limit(3)->get();
echo ToonExport::fromCollection($users, 'users', ['id', 'name', 'email']);
```

## Documentation

- [GitHub Repository](https://github.com/azzoelabbar/laravel-toon-export)
- [Packagist](https://packagist.org/packages/azzoelabbar/laravel-toon-export)

## Troubleshooting

**Command not found?**
```bash
php artisan package:discover
composer dump-autoload
```

**Class not found?**
```bash
composer dump-autoload
php artisan config:clear
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

Built by [Hashed](https://github.com/azzoelabbar/laravel-toon-export) for the Laravel community, optimized for AI/LLM workflows.
