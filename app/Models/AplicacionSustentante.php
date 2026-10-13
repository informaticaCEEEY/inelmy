<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AplicacionSustentante extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'aplicacion_sustentante';
    protected $fillable = ['aplicacion_id', 'sustentante_id', 'estatus_aplicacion_id'];

    public function aplicacion(): BelongsTo
    {
        return $this->belongsTo(Aplicacion::class);
    }

    public function sustentante(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'sustentante_id');
    }

    public function estatusAplicacion(): BelongsTo
    {
        return $this->belongsTo(EstatusAplicacion::class);
    }
}
