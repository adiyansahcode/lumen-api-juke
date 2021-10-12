<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provinces';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'alt_name',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * The default attributes for sorting API.
     *
     * @var string
     */
    public $defaultSortColumn = 'name';
    public $defaultSortOperator = 'asc';

    /**
     * The attributes for sorting API.
     *
     * @var array
     */
    public $sortable = [
        'name',
    ];

    /**
     * The attributes for filtering API.
     *
     * @var array
     */
    public $filterable = [
        'name',
    ];

    public function regency(): object
    {
        return $this->hasMany(Regency::class, 'province_id');
    }
}
