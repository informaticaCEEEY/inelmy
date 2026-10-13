<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreguntaVersion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pregunta_version';
    protected $fillable = ['estatus_version_id', 'pregunta_id', 'tipo_pregunta_id',
        'recurso_version_id', 'rubrica_id', 'version', 'enunciado'];

    public function estatusVersion(): BelongsTo
    {
        return $this->belongsTo(EstatusVersion::class);
    }

    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class);
    }

    public function tipoPregunta(): BelongsTo
    {
        return $this->belongsTo(TipoPregunta::class);
    }

    public function recursoVersion(): BelongsTo
    {
        return $this->belongsTo(RecursoVersion::class);
    }

    public function rubrica(): BelongsTo
    {
        return $this->belongsTo(Rubrica::class);
    }
}
