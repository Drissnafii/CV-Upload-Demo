# CV Upload - Demo

## 3. Create User Migration and Model

Laravel already comes with the User model and migration, but we need to run the migration:

```bash
# Run the existing migrations
php artisan migrate
```

## 4. Create CV Migration

```bash
# Create the migration for CVs table
php artisan make:model resume --all
```

Edit the migration file in `database/migrations/xxxx_xx_xx_create_cvs_table.php` to match your table structure:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('file_path');
            $table->string('file_name');
            $table->integer('file_size');
            $table->timestamps();

            $table->foreign('user_id')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cvs');
    }
};
```

```bash
# Run the migration
php artisan migrate
```

## 5.Resume Model

Edit the model file in `app/Models/CV.php`:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CV extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cvs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'file_path',
        'file_name',
        'file_size',
    ];

    /**
     * Get the user that owns the CV.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

## 6. Update User Model

Edit the User model in `app/Models/User.php` to add the relationship with CVs:

```php
// Add this method to the User model
/**
 * Get the CVs for the user.
 */
public function cvs()
{
    return $this->hasMany(CV::class);
}
```

## 7. Set Up File Storage

```bash
# Create symbolic link for storage
php artisan storage:link
```

## 8. Create CV Controller

```bash
# Create the controller for CV operations
php artisan make:controller Api/CVController
```

Edit the controller file in `app/Http/Controllers/Api/CVController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CVController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message'=> 'Unauthorized'
            ],401);
        }

        $cvs = $user->cvs;

        return response()->json([
            'status' => 'success',
            'data' => $cvs
        ]);
    }

    public function store(Request $request)
    {
        try{
            $validator = $request->validate([
                "title"=> "required|string|max:255",
                "cv_file"=> "required|file|mimes:pdf",
                "user_id"=> "required",
            ]);
        }catch(\Exception $e){
            return "erro" . $e->getMessage();
        }

        $file = $request->file('cv_file');
        $fileName = time() . '_' . Str::slug($request->title) . '.pdf';

        // Store file locally
        $filePath = $file->storeAs('cvs/' . Auth::id(), $fileName, 'public');

        $cv = resume::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $file->getSize()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'CV uploaded successfully',
            'data' => $cv
        ], 201);
    }

    public function show(CV $cv)
    {
        // Check if the authenticated user owns the CV
        if ($cv->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access to CV'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $cv
        ]);
    }
}
```


## 9. Define API Routes

Edit `routes/web.php`:

```php
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CVController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
    Route::get('/cvs', [CVController::class, 'index']);
    Route::post('/cvs', [CVController::class, 'store']);
    Route::get('/cvs/{cv}', [CVController::class, 'show']);
```

## 12. Start the Server

```bash
# Start the Laravel development server
php artisan serve
```

## Testing with Postman

### 1. Register a User

- Method: POST
- URL: `http://localhost:8000/api/register`
- Body (form-data):
  - name: Test User
  - email: test@example.com
  - password: password123
  - password_confirmation: password123

### 2. Login

- Method: POST
- URL: `http://localhost:8000/api/login`
- Body (form-data):
  - email: test@example.com
  - password: password123

Save the token from the response.

### 3. Upload a CV

- Method: POST
- URL: `http://localhost:8000/api/cvs`
- Headers:
  - Authorization: Bearer {your_token}
- Body (form-data):
  - title: My Resume
  - cv_file: [select a PDF or Word document]

### 4. List All CVs

- Method: GET
- URL: `http://localhost:8000/api/cvs`
- Headers:
  - Authorization: Bearer {your_token}

### 5. View a Specific CV

- Method: GET
- URL: `http://localhost:8000/api/cvs/{cv_id}`
- Headers:
  - Authorization: Bearer {your_token}

## Summary of Artisan Commands Used

```bash
# Project setup
composer create-project laravel/laravel cv-upload-demo
cd cv-upload-demo

# Database
php artisan migrate

# Create model and migration
php artisan make:migration create_cvs_table
php artisan make:model CV

# File storage
php artisan storage:link

# Create controllers
php artisan make:controller Api/CVController
php artisan make:controller Api/AuthController

# Authentication
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

# Run server
php artisan serve
```

This simplified guide provides all the necessary steps to implement basic CV upload and display functionality in Laravel 12, without using separate service folders or form request classes, and matches your specific table structure.
