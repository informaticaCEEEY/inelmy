<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RubricaCriterio extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rubrica_criterio';
    protected $fillable = ['rubrica_id', 'nombre', 'peso'];

    public function rubrica(): BelongsTo
    {
        return $this->belongsTo(Rubrica::class);
    }
}
