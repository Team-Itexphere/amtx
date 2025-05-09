<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'fleet',
        'com_name',
        'own_name',
        'str_addr',
        'city',
        'state',
        'zip_code',
        'str_phone',
        'cp_name',
        'cp_phone',
        'own_email',
        'email_list',
        'fac_id',
        'com_to_inv',
        'cus_type',
        'role',
        'login',
        'work_for',
        'rec_logs',
        'switch_as',
        'deleted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function getRoleLabelAttribute()
    {
        $roles = [
            1 => 'Super Admin',
            2 => 'Admin',
            3 => 'Office Staff',
            4 => 'Field Tech Supervisor',
            5 => 'Technician',
            6 => 'Store',
        ];
    
        return $roles[$this->role] ?? 'Unknown';
    }
    
    public function getStrAddressAttribute()
    {
        $addressParts = array_filter([
            $this->attributes['str_addr'] ?? null,
            $this->attributes['city'] ?? null,
            $this->attributes['state'] ?? null,
            $this->attributes['zip_code'] ?? null,
        ]);

        return implode(', ', $addressParts);
    }

    public function invoices()
    {
        return $this->hasMany(Invoices::class, 'customer_id');
    }
    
    public function maintain_logs()
    {
        return $this->hasMany(Maintain_logs::class, 'cus_id');
    }

    /*public function route_lists()
    {
        return $this->hasMany(Route_lists::class, 'tech_id');
    }*/

    public function cus_notes()
    {
        return $this->hasMany(Cus_notes::class, 'cus_id');
    }

    public function fleet()
    {
        return $this->belongsTo(Fleets::class, 'fleet_id');
    }

    public function cus_licenses()
    {
        return $this->hasMany(Cus_licenses::class, 'customer_id');
    }
    
    public function cus_sir_inv_docs()
    {
        return $this->hasMany(Cus_sir_inv_docs::class, 'customer_id');
    }
    
    public function site_info()
    {
        return $this->hasOne(Site_infos::class, 'customer_id');
    }
    
    public function comp_docs()
    {
        return $this->hasMany(Comp_docs::class, 'customer_id');
    }
    
    public function ro_locations()
    {
        return $this->hasMany(Ro_locations::class, 'cus_id');
    }

    public function testings()
    {
        return $this->hasMany(Testings::class, 'cus_id');
    }
    
    public function tech_testings()
    {
        return $this->hasMany(Testings::class, 'tech_id');
    }
    
    public function pictures()
    {
        return $this->hasMany(Pictures::class, 'cus_id');
    }

    public function work_orders()
    {
        return $this->hasMany(Work_orders::class, 'tech_id');
    }
    
    public function cus_work_orders()
    {
        return $this->hasMany(Work_orders::class, 'customer_id');
    }
    
    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'customers_stores', 'customer_id', 'store_id');
    }
    
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'customers_stores', 'store_id', 'customer_id');
    }
    
    public function route_lists(): BelongsToMany
    {
        return $this->belongsToMany(Route_lists::class, 'user_route_lists', 'user_id', 'route_list_id');
    }
}
