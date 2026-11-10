<?php

namespace App\Enums;

enum TipoEvento: string
{
    case Congresso = 'Congresso';
    case Encontro = 'Encontro';
    case Seminario = 'Seminário';
    case MesaRedonda = 'Mesa redonda';
    case Simposio = 'Simpósio';
    case Painel = 'Painel';
    case Forum = 'Fórum';
    case Conferencia = 'Conferência';
    case Jornada = 'Jornada';
    case Cursos = 'Cursos';
    case Coloquio = 'Colóquio';
    case Semana = 'Semana';
    case Workshop = 'Workshop';
    case Outro = 'outro';

    public function label(): string
    {
        return match ($this) {
            self::Outro => 'Outro',
            default => $this->value,
        };
    }

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
