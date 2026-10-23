<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recurso extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recurso';
    protected $fillable = ['tipo_recurso_id', 'clave'];

    public function tipoRecurso()
    {
        return $this->belongsTo(TipoRecurso::class);
    }

    public function versiones() :HasMany
    {
        return $this->hasMany(RecursoVersion::class);
    }
}
