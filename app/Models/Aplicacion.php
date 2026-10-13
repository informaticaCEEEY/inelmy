<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aplicacion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'aplicacion';
    protected $fillable = ['instrumento_version_id', 'nombre', 'fecha_inicio', 'fecha_fin'];

    public function instrumentoVersion(): BelongsTo
    {
        return $this->belongsTo(InstrumentoVersion::class);
    }
}
