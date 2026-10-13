<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpcionRespuesta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opcion_respuesta';
    protected $fillable = ['pregunta_version_id', 'contenido', 'orden', 'es_correcta'];

    public function preguntaVersion(): BelongsTo
    {
        return $this->belongsTo(PreguntaVersion::class);
    }
}
