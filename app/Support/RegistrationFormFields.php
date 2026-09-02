<?php

namespace App\Support;

class RegistrationFormFields
{
    public const ALLOWED_FILE_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp',
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'csv',
        'odt',
        'ods',
        'ppt',
        'pptx',
        'txt',
        'rtf',
    ];

    public static function types(): array
    {
        return [
            'text' => [
                'label' => 'Texto',
                'description' => 'Resposta curta em texto livre.',
                'icon' => 'bi-input-cursor-text',
            ],
            'email' => [
                'label' => 'E-mail',
                'description' => 'Endereço de e-mail adicional.',
                'icon' => 'bi-envelope',
            ],
            'select' => [
                'label' => 'Seleção',
                'description' => 'Lista de opções para escolha única.',
                'icon' => 'bi-list-check',
            ],
            'file' => [
                'label' => 'Arquivo',
                'description' => 'Upload de comprovante ou documento.',
                'icon' => 'bi-paperclip',
            ],
            'date' => [
                'label' => 'Data',
                'description' => 'Campo de data.',
                'icon' => 'bi-calendar-event',
            ],
            'endereco' => [
                'label' => 'Endereço',
                'description' => 'CEP, rua, bairro, cidade, UF e número.',
                'icon' => 'bi-geo-alt',
            ],
            'cpf' => [
                'label' => 'CPF',
                'description' => 'CPF com validação.',
                'icon' => 'bi-person-vcard',
            ],
            'contato' => [
                'label' => 'Contato',
                'description' => 'Telefone ou celular.',
                'icon' => 'bi-telephone',
            ],
        ];
    }

    public static function typeKeys(): array
    {
        return array_keys(self::types());
    }

    public static function label(string $type): string
    {
        return self::types()[$type]['label'] ?? ucfirst($type);
    }

    public static function allowedFileExtensions(): array
    {
        return self::ALLOWED_FILE_EXTENSIONS;
    }

    public static function allowedFileMimesRule(): string
    {
        return 'mimes:'.implode(',', self::allowedFileExtensions());
    }

    public static function fileAcceptAttribute(): string
    {
        return implode(',', array_map(fn ($extension) => '.'.$extension, self::allowedFileExtensions()));
    }

    public static function allowedFileTypesMessage(): string
    {
        return 'Tipo de arquivo não permitido. Envie apenas imagens ou documentos nos formatos: '
            .implode(', ', self::allowedFileExtensions()).'.';
    }
}
