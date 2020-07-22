<?php

namespace App\Models\Workbook;

use App\Models\Workbook\Traits\Attribute\WorkbookAttribute;
use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workbook extends Model
{
    use ModelTrait,
        WorkbookAttribute,
        SoftDeletes{
          //   WorkbookAttribute::getActionButtonsAttribute insteadof ModelTrait;
        }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    protected $fillable = [
        'name',
        'type',
        'items',
		'items_global',
		'created_by',
		'updated_by',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('access.workbooks_table');
    }
}
