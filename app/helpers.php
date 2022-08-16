<?php

use App\Models\Chat\CanalMensagem;
use App\Models\Chat\Mensagem;
use App\Models\Manifest\Manifest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

if (!function_exists('canaisAguardandoRespostaNotification')) {
    function canaisAguardandoRespostaNotification()
    {
        $qtdNotifications =  CanalMensagem::where('id_status', canalMensagem()::STATUS_AGUARDANDO_RESPOSTA)->count();
        Cache::put('qtdNotifications' . auth()->user()->id, $qtdNotifications);
        if ($qtdNotifications  > 99) {
            return '99+';
        } else {
            return $qtdNotifications;
        }
    }
}

if (!function_exists('manifestacoesNaoEncerradasNotification')) {
    function manifestacoesNaoEncerradasNotification()
    {
        $qtdNotifications =  Manifest::where('id_situacao', "!=", Manifest::SITUACAO_CONCLUIDA)->count();
        if ($qtdNotifications  > 99) {
            return '99+';
        } else {
            return $qtdNotifications;
        }
    }
}

if (!function_exists('carbonDiffInDays')) {
    function carbonDiffInDays($dataInicial)
    {
        return Carbon::parse($dataInicial)->diffInDays(Carbon::now());
    }
}

if (!function_exists('carbonDiffInHours')) {
    function carbonDiffInHours($dataInicial)
    {
        return Carbon::parse($dataInicial)->diffInHours(Carbon::now());
    }
}

if (!function_exists('carbonDiffInHoursMinusDays')) {
    function carbonDiffInHoursMinusDays($dataInicial)
    {
        return Carbon::parse($dataInicial)
            ->addHours(Carbon::parse($dataInicial)->diffInDays(Carbon::now()) * 24)
            ->diffInHours(Carbon::now());
    }
}

if (!function_exists('carbonDiffInMinutes')) {
    function carbonDiffInMinutes($dataInicial)
    {
        return Carbon::parse($dataInicial)->diffInMinutes(Carbon::now());
    }
}

if (!function_exists('carbonDiffInMinutesMinusHours')) {
    function carbonDiffInMinutesMinusHours($dataInicial)
    {
        return Carbon::parse($dataInicial)
            ->addMinutes(Carbon::parse($dataInicial)->diffInHours(Carbon::now()) * 60)
            ->diffInMinutes(Carbon::now());
    }
}

if (!function_exists('dateAndFormat')) {
    function dateAndFormat($data = null, $format = 'Y-m-d')
    {
        if (is_null($data)) {
            $data = Carbon::now();
        }
        return Carbon::parse($data)->format($format);
    }
}

if (!function_exists('formatarDataHora')) {
    function formatarDataHora($data = null, $format = 'd/m/Y \à\s H:i\h')
    {
        if (is_null($data)) {
            $data = Carbon::now();
        }
        return Carbon::parse($data)->format($format);
    }
}

if (!function_exists('carbon')) {
    function carbon()
    {
        return new Carbon;
    }
}

if (!function_exists('canalMensagem')) {
    function canalMensagem()
    {
        return CanalMensagem::class;
    }
}

if (!function_exists('mensagem')) {
    function mensagem()
    {
        return Mensagem::class;
    }
}

if (!function_exists('manifest')) {
    function manifest()
    {
        return Manifest::class;
    }
}

if (!function_exists('routeClass')) {
    function routeClass()
    {
        return Route::class;
    }
}
