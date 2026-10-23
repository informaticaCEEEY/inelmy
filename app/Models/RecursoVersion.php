<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecursoVersion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recurso_version';
    protected $fillable = ['recurso_id', 'estatus_version_id',
        'titulo', 'version', 'url_contenido', 'transcripcion'];

    public function recurso(): BelongsTo
    {
        return $this->belongsTo(Recurso::class);
    }

    public function estatusVersion(): BelongsTo
    {
        return $this->belongsTo(EstatusVersion::class);
    }
}
