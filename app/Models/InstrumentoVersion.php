<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstrumentoVersion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instrumento_version';
    protected $fillable = ['estatus_version_id', 'instrumento_id', 'version', 'notas'];

    public function estatusVersion(): BelongsTo
    {
        return $this->belongsTo(EstatusVersion::class);
    }

    public function instrumento(): BelongsTo
    {
        return $this->belongsTo(Instrumento::class);
    }
}
