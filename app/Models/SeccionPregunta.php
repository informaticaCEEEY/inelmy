<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeccionPregunta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seccion_pregunta';
    protected $fillable = ['seccion_id', 'pregunta_version_id', 'orden', 'puntaje'];

    public function preguntaVersion(): BelongsTo
    {
        return $this->belongsTo(PreguntaVersion::class);
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }
}
