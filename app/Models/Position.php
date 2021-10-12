<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'position';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
    public $defaultSortColumn = 'created_at';
    public $defaultSortOperator = 'desc';

    /**
     * The attributes for sorting API.
     *
     * @var array
     */
    public $sortable = [
        'created_at',
        'updated_at',
        'name',
    ];

    /**
     * The attributes for filtering API.
     *
     * @var array
     */
    public $filterable = [
        'created_at',
        'updated_at',
        'name',
    ];

    public function employee(): object
    {
        return $this->hasMany(Employee::class, 'position_id');
    }
}
