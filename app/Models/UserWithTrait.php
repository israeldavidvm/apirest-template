<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidatorInstance;
use Illuminate\Validation\Rule;
use Exception;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

use Illuminate\Support\MessageBag;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class UserWithTrait extends Authenticatable
{

    use \Israeldavidvm\EloquentTraits\AttributesTrait;

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token'
    ];

	public function initAttributes($attributes) {

		$this->name=$attributes['name'];
        $this->email=$attributes['email'];
        $this->password=$attributes['password'];
      
    }

	public static function generateValidator (array $arrayattributes): ValidatorInstance {

        $rules = [
            'email' => [
                'required',
                'max:255',
                'email:dns,rfc', // Formato de email válido
            ],
            'password' => [
                'required',
                'max:255',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[.$%@!&*+]).*$/',
            ],
            'action' => [
                'required',
                Rule::in(['login', 'create', 'update', 'updateOrCreate']),
            ]
        ];

        $validator = Validator::make($arrayattributes, $rules);

        // Validación condicional para login
        $validator->sometimes('email', 'exists:users,email', function ($input) {
            return strtolower($input->action) === 'login';
        });

        // Validación condicional para crear usuario
        $validator->sometimes('name', ['required', 'max:255'], function ($input) {
            return strtolower($input->action) === 'create';
        });

        $validator->sometimes('email', 'unique:users,email', function ($input) {
            return strtolower($input->action) === 'create';
        });

        // Validación condicional para actualizar usuario
        $validator->sometimes('name', 'max:255', function ($input) {
            return in_array(strtolower($input->action), ['update', 'updateOrCreate']);
        });

        return $validator;
    }

}