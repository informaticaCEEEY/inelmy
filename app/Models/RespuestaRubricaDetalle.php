<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespuestaRubricaDetalle extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'respuesta_rubrica_detalle';
    protected $fillable = ['respuesta_id', 'criterio_nivel_id', 'rubrica_criterio_id'];

    public function respuesta(): BelongsTo
    {
        return $this->belongsTo(Respuesta::class);
    }

    public function criterioNivel(): BelongsTo
    {
        return $this->belongsTo(CriterioNivel::class);
    }

    public function rubricaCriterio(): BelongsTo
    {
        return $this->belongsTo(RubricaCriterio::class);
    }
}
