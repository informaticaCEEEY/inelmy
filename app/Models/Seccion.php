<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seccion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seccion';
    protected $fillable = ['instrumento_version_id', 'tipo_seccion_id', 'orden', 'tiempo_asignado'];

    public function instrumentoVersion(): BelongsTo
    {
        return $this->belongsTo(InstrumentoVersion::class);
    }

    public function tipoSeccion(): BelongsTo
    {
        return $this->belongsTo(TipoSeccion::class);
    }
}
