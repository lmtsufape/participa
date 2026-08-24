<?php

namespace App\Enums;

enum StatusForm: string
{
    case Rascunho = 'rascunho';
    case Publicado = 'publicado';
    case Substituido = 'substituido';
    case Arquivado = 'arquivado';

    public function podeEditar(): bool
    {
        return $this === self::Rascunho;
    }

    public function estaPublicado(): bool
    {
        return $this === self::Publicado;
    }

    public function estaNoHistorico(): bool
    {
        return in_array($this, [
            self::Substituido,
            self::Arquivado,
        ], true);
    }

    public function label(): string
    {
        return match ($this) {
            self::Rascunho => 'Rascunho',
            self::Publicado => 'Publicado',
            self::Substituido => 'Substituído',
            self::Arquivado => 'Arquivado',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Publicado =>
                'bg-success-subtle text-success-emphasis',

            self::Rascunho =>
                'bg-warning-subtle text-warning-emphasis',

            self::Substituido =>
                'bg-secondary-subtle text-secondary-emphasis',

            self::Arquivado =>
                'bg-light text-dark',
        };
    }
}
