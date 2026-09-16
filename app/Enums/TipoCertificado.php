<?php

namespace App\Enums;

enum TipoCertificado: int
{
    case Apresentador = 1;
    case ComissaoCientifica = 2;
    case ComissaoOrganizadora = 3;
    case Revisor = 4;
    case Participante = 5;
    case Palestrante = 6;
    case CoordenadorComissaoCientifica = 7;
    case OutrasComissoes = 8;
    case InscritoAtividade = 9;
    case Inscrito = 10;
    case Credenciado = 11;

    public function label(): String
    {
        return match ($this) {
            self::Apresentador => 'Apresentador de Trabalho',
            self::ComissaoCientifica => 'Membro da Comissão Científica',
            self::ComissaoOrganizadora => 'Membro da Comissão Organizadora',
            self::Revisor => 'Revisor/Avaliador',
            self::Participante => 'Participante',
            self::Palestrante => 'Palestrante',
            self::CoordenadorComissaoCientifica => 'Coordenador da Comissão Científica',
            self::OutrasComissoes => 'Membro de Outra Comissão',
            self::InscritoAtividade => 'Inscrito em Atividade',
            self::Inscrito => 'Inscrito no Evento',
            self::Credenciado => 'Credenciado (Com Presença Confirmada)',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $tipo) => [
                $tipo->value => $tipo->label(),
            ])
            ->all();
    }
}
