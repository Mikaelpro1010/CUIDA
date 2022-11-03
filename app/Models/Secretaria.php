<?php

namespace App\Models;

use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Unidade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Cache;

class Secretaria extends Model
{
    protected $table = "secretarias";
    protected $guarded = [];

    public function setSiglaAttribute($value)
    {
        $this->attributes['sigla'] = strtoupper($value);
    }

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidade::class, 'secretaria_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function avaliacoes(): HasManyThrough
    {
        return $this->hasManyThrough(Avaliacao::class, Unidade::class, 'secretaria_id', 'unidade_secr_id');
    }

    public static function getResumoSecretariaId($id): array
    {
        $secretaria = Secretaria::find($id);
        return $secretaria->getResumo();
    }

    public static function getResumoSecretariaAll(): array
    {
        $secretarias = Secretaria::query()->with('unidades')->get();

        $resumoSecretarias = [
            'qtd' => 0,
            'notas1' => 0,
            'notas2' => 0,
            'notas3' => 0,
            'notas4' => 0,
            'notas5' => 0,
        ];

        foreach ($secretarias as $secretaria) {
            $resumoSecretaria = $secretaria->getResumo();
            $resumoSecretarias['qtd'] += $resumoSecretaria['qtd'];
            $resumoSecretarias['notas1'] += $resumoSecretaria['notas1'];
            $resumoSecretarias['notas2'] += $resumoSecretaria['notas2'];
            $resumoSecretarias['notas3'] += $resumoSecretaria['notas3'];
            $resumoSecretarias['notas4'] += $resumoSecretaria['notas4'];
            $resumoSecretarias['notas5'] += $resumoSecretaria['notas5'];
        }

        return $resumoSecretarias;
    }

    public function getResumo(): array
    {
        $resumoSecretaria = [
            'qtd' => 0,
            'notas1' => 0,
            'notas2' => 0,
            'notas3' => 0,
            'notas4' => 0,
            'notas5' => 0,
        ];

        foreach ($this->unidades as $unidade) {
            $resumoUnidade = $unidade->getResumoFromCache();
            $resumoSecretaria['qtd'] += $resumoUnidade['qtd'];
            $resumoSecretaria['notas1'] += $resumoUnidade['notas1'];
            $resumoSecretaria['notas2'] += $resumoUnidade['notas2'];
            $resumoSecretaria['notas3'] += $resumoUnidade['notas3'];
            $resumoSecretaria['notas4'] += $resumoUnidade['notas4'];
            $resumoSecretaria['notas5'] += $resumoUnidade['notas5'];
        }

        $this->update([
            'nota' => $this->unidades->avg('nota')
        ]);

        return $resumoSecretaria;
    }
}
