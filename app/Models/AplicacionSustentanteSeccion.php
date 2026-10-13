<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AplicacionSustentanteSeccion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'aplicacion_sustentante_seccion';
    protected $fillable = ['aplicacion_sustentante_id', 'seccion_id', 'estatus_seccion_id', 'inicio', 'fin'];

    public function aplicacionSustentante(): BelongsTo
    {
        return $this->belongsTo(AplicacionSustentante::class);
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    public function estatusSeccion(): BelongsTo
    {
        return $this->belongsTo(EstatusSeccion::class);
    }
}
