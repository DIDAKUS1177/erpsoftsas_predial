<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrValidation extends Model
{
    use HasFactory;

    protected $table = 'predios_validaciones_qr';

    protected $fillable = [
        'token',
        'id_predio',
        'certificado_numero',
        'propietario_principal',
        'fecha_expedicion',
        'fecha_validez',
        'is_validated',
        'validated_at',
        'validated_ip',
        'validated_user_agent',
        'max_validations',
        'validation_count'
    ];

    protected $casts = [
        'fecha_expedicion' => 'date',
        'fecha_validez' => 'date',
        'validated_at' => 'datetime',
        'is_validated' => 'boolean'
    ];

    public function predio()
    {
        return $this->belongsTo(Predio::class, 'id_predio');
    }

    public function markAsValidated($request)
    {
        $this->increment('validation_count');
        $this->update([
            'validated_at' => now(),
            'validated_ip' => $request->ip(),
            'validated_user_agent' => $request->userAgent()
        ]);
    }

    public function isExpired()
    {
        return now()->isAfter($this->fecha_validez);
    }

    public function isValid()
    {
        return $this->validation_count < $this->max_validations && !$this->isExpired();
    }

    public function hasRemainingValidations()
    {
        return $this->validation_count < $this->max_validations;
    }

    public function getRemainingValidations()
    {
        return max(0, $this->max_validations - $this->validation_count);
    }
}