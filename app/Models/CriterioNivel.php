<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriterioNivel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'criterio_nivel';
    protected $fillable = ['rubrica_criterio_id', 'nombre', 'descripcion', 'puntaje'];

    public function rubricaCriterio(): BelongsTo
    {
        return $this->belongsTo(RubricaCriterio::class);
    }

}
