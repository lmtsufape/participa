<?php

namespace App\Support;

class EventoModules
{
    public static function definitions(): array
    {
        return [
            [
                'key' => 'inscricoes',
                'title' => 'Inscrições',
                'description' => 'Permite que participantes façam inscrição no evento.',
                'enabled' => [
                    'field' => 'modinscricao',
                    'storage' => 'form_evento',
                    'label' => 'Habilitar inscrições',
                ],
                'options' => [
                    [
                        'field' => 'modvalidarinscricao',
                        'storage' => 'form_evento',
                        'label' => 'Validar inscrição',
                        'help' => 'Ative quando as inscrições precisam de aprovação ou integração de pagamento antes da confirmação.',
                    ],
                ],
            ],
            [
                'key' => 'programacao',
                'title' => 'Programação',
                'description' => 'Exibe a programação pública do evento.',
                'enabled' => [
                    'field' => 'modprogramacao',
                    'storage' => 'form_evento',
                    'label' => 'Habilitar programação',
                ],
                'options' => [
                    [
                        'field' => 'exibir_calendario',
                        'storage' => 'evento',
                        'column' => 'exibir_calendario_programacao',
                        'label' => 'Exibir em calendário',
                    ],
                    [
                        'field' => 'exibir_pdf',
                        'storage' => 'evento',
                        'column' => 'exibir_pdf',
                        'label' => 'Exibir PDF enviado',
                    ],
                    [
                        'field' => 'modarquivo',
                        'storage' => 'evento',
                        'column' => 'modarquivo',
                        'label' => 'Exibir arquivo adicional',
                    ],
                ],
            ],
            [
                'key' => 'organizacao',
                'title' => 'Organização e Apoio',
                'description' => 'Mostra as informações de organização, apoio e parceiros.',
                'enabled' => [
                    'field' => 'modorganizacao',
                    'storage' => 'form_evento',
                    'label' => 'Habilitar organização e apoio',
                ],
                'options' => [],
            ],
            [
                'key' => 'submissoes',
                'title' => 'Submissões de Trabalhos',
                'description' => 'Permite submissão de trabalhos e aplica regras para coautores.',
                'enabled' => [
                    'field' => 'modsubmissao',
                    'storage' => 'form_evento',
                    'label' => 'Habilitar submissões',
                ],
                'options' => [
                    [
                        'field' => 'modinscritonoevento',
                        'storage' => 'form_evento',
                        'label' => 'Coautores precisam estar inscritos no evento',
                    ],
                    [
                        'field' => 'modinscritonaplataforma',
                        'storage' => 'form_evento',
                        'label' => 'Coautores precisam estar cadastrados na plataforma',
                    ],
                ],
            ],
        ];
    }

    public static function requestFields(): array
    {
        $fields = [];

        foreach (self::definitions() as $module) {
            $fields[] = $module['enabled']['field'];

            foreach ($module['options'] as $option) {
                $fields[] = $option['field'];
            }
        }

        return $fields;
    }
}
