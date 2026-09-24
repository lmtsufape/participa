<?php

namespace App\Enums;

enum TipoArquivo: string
{
    // Documentos
    case PDF = 'pdf';
    case DOCX = 'docx';
    case ODT = 'odt';

    // Apresentações
    case ODP = 'odp';
    case PPTX = 'pptx';

    // Planilhas
    case ODS = 'ods';
    case XLSX = 'xlsx';
    case CSV = 'csv';

    // Compactados
    case ZIP = 'zip';

    // Áudio
    case MP3 = 'mp3';
    case OGG = 'ogg';
    case WAV = 'wav';

    // Vídeo
    case MP4 = 'mp4';
    case OGV = 'ogv';
    case MPG = 'mpg';
    case MPEG = 'mpeg';
    case MKV = 'mkv';
    case AVI = 'avi';

    // Imagens
    case JPG = 'jpg';
    case JPEG = 'jpeg';
    case PNG = 'png';
    case SVG = 'svg';

    public function label(): string
    {
        return match ($this) {
            self::PDF => 'PDF',

            self::DOCX => 'Documento Word',
            self::ODT => 'Documento OpenDocument',

            self::ODP => 'Apresentação OpenDocument',
            self::PPTX => 'Apresentação PowerPoint',

            self::ODS => 'Planilha OpenDocument',
            self::XLSX => 'Planilha Excel',
            self::CSV => 'Arquivo CSV',

            self::ZIP => 'Arquivo compactado',

            self::MP3 => 'Áudio MP3',
            self::OGG => 'Áudio OGG',
            self::WAV => 'Áudio WAV',

            self::MP4 => 'Vídeo MP4',
            self::OGV => 'Vídeo OGV',
            self::MPG => 'Vídeo MPG',
            self::MPEG => 'Vídeo MPEG',
            self::MKV => 'Vídeo MKV',
            self::AVI => 'Vídeo AVI',

            self::JPG => 'Imagem JPG',
            self::JPEG => 'Imagem JPEG',
            self::PNG => 'Imagem PNG',
            self::SVG => 'Imagem SVG',
        };
    }

    public function extensao(): string
    {
        return ".{$this->value}";
    }

    public function categoria(): string
    {
        return match ($this) {
            self::PDF,
            self::DOCX,
            self::ODT => 'Documentos',

            self::ODP,
            self::PPTX => 'Apresentações',

            self::ODS,
            self::XLSX,
            self::CSV => 'Planilhas',

            self::ZIP => 'Arquivos compactados',

            self::MP3,
            self::OGG,
            self::WAV => 'Áudio',

            self::MP4,
            self::OGV,
            self::MPG,
            self::MPEG,
            self::MKV,
            self::AVI => 'Vídeo',

            self::JPG,
            self::JPEG,
            self::PNG,
            self::SVG => 'Imagens',
        };
    }

    public function mimeTypes(): array
    {
        return match ($this) {
            self::PDF => [
                'application/pdf',
            ],

            self::DOCX => [
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],

            self::ODT => [
                'application/vnd.oasis.opendocument.text',
            ],

            self::ODP => [
                'application/vnd.oasis.opendocument.presentation',
            ],

            self::PPTX => [
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ],

            self::ODS => [
                'application/vnd.oasis.opendocument.spreadsheet',
            ],

            self::XLSX => [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],

            self::CSV => [
                'text/csv',
                'text/plain',
            ],

            self::ZIP => [
                'application/zip',
                'application/x-zip-compressed',
            ],

            self::MP3 => [
                'audio/mpeg',
            ],

            self::OGG => [
                'audio/ogg',
            ],

            self::WAV => [
                'audio/wav',
                'audio/x-wav',
            ],

            self::MP4 => [
                'video/mp4',
            ],

            self::OGV => [
                'video/ogg',
            ],

            self::MPG,
            self::MPEG => [
                'video/mpeg',
            ],

            self::MKV => [
                'video/x-matroska',
            ],

            self::AVI => [
                'video/x-msvideo',
            ],

            self::JPG,
            self::JPEG => [
                'image/jpeg',
            ],

            self::PNG => [
                'image/png',
            ],

            self::SVG => [
                'image/svg+xml',
            ],
        };
    }

    public function accept(): string
    {
        return implode(',', [
            $this->extensao(),
            ...$this->mimeTypes(),
        ]);
    }

    public static function valores(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function agrupadosPorCategoria(): array
    {
        $categorias = [];

        foreach (self::cases() as $tipo) {
            $categorias[$tipo->categoria()][] = $tipo;
        }

        return $categorias;
    }
}