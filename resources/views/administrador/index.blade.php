@extends('layouts.app')

@section('css')
    <style>
        .admin-dashboard {
            color: #071b1eff;
        }

        .admin-dashboard__header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 1.5rem;
            margin-bottom: 1.75rem;
        }

        .admin-dashboard__eyebrow {
            color: #196572ff;
            font-size: .875rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: .35rem;
        }

        .admin-dashboard__title {
            color: #071b1eff;
            font-size: clamp(1.75rem, 3vw, 2.55rem);
            font-weight: 800;
            line-height: 1.1;
            margin: 0;
        }

        .admin-dashboard__subtitle {
            color: #4b5b60;
            max-width: 680px;
            margin: .75rem 0 0;
            font-size: 1rem;
        }

        .admin-dashboard__badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            border: 1px solid rgba(25, 101, 114, .18);
            border-radius: 999px;
            color: #114048ff;
            background: #fff;
            padding: .65rem 1rem;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 10px 24px rgba(7, 27, 30, .06);
        }

        .admin-action-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .admin-action-card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 240px;
            padding: 1.35rem;
            overflow: hidden;
            text-decoration: none;
            color: #071b1eff;
            background: #fff;
            border: 1px solid rgba(17, 64, 72, .12);
            border-radius: 8px;
            box-shadow: 0 14px 30px rgba(7, 27, 30, .08);
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .admin-action-card:hover {
            color: #071b1eff;
            border-color: rgba(25, 101, 114, .34);
            box-shadow: 0 18px 38px rgba(7, 27, 30, .14);
            transform: translateY(-3px);
        }

        .admin-action-card::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 5px;
            background: linear-gradient(90deg, #114048ff, #196572ff);
        }

        .admin-action-card__top {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .admin-action-card__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            border-radius: 8px;
            color: #fff;
            background: #114048ff;
            font-size: 1.55rem;
            flex: 0 0 auto;
        }

        .admin-action-card__title {
            font-size: 1.45rem;
            font-weight: 800;
            margin-bottom: .5rem;
        }

        .admin-action-card__text {
            color: #516266;
            margin-bottom: 1.5rem;
            line-height: 1.45;
        }

        .admin-action-card__footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-top: auto;
            color: #196572ff;
            font-weight: 800;
        }

        @media (max-width: 991.98px) {
            .admin-dashboard__header {
                align-items: flex-start;
                flex-direction: column;
            }

            .admin-action-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $actions = [
            [
                'title' => 'Eventos',
                'description' => 'Gerencie eventos publicados, configurações, programação e acesso das equipes.',
                'route' => route('admin.eventos'),
                'icon' => 'bi-calendar2-event',
            ],
            [
                'title' => 'Usuários',
                'description' => 'Consulte cadastros, atualize dados institucionais e acompanhe permissões.',
                'route' => route('admin.users'),
                'icon' => 'bi-people',
            ],
            [
                'title' => 'Confirmação de inscrição',
                'description' => 'Valide documentos, certificados e comprovantes vinculados às inscrições.',
                'route' => route('admin.relatorio.form'),
                'icon' => 'bi-patch-check',
            ],
        ];
    @endphp

    <div class="container admin-dashboard">
        <div class="admin-dashboard__header">
            <div>
                <div class="admin-dashboard__eyebrow">{{ __('Painel administrativo') }}</div>
                <h1 class="admin-dashboard__title">{{ Auth()->user()->name }}</h1>
            </div>
            <span class="admin-dashboard__badge">
                <i class="bi bi-shield-check"></i>
                {{ __('Perfil: Administrador') }}
            </span>
        </div>

        <div class="admin-action-grid">
            @foreach ($actions as $action)
                <a href="{{ $action['route'] }}" class="admin-action-card">
                    <div class="admin-action-card__top">
                        <span class="admin-action-card__icon">
                            <i class="bi {{ $action['icon'] }}"></i>
                        </span>
                    </div>
                    <div class="admin-action-card__title">{{ __($action['title']) }}</div>
                    <p class="admin-action-card__text">{{ __($action['description']) }}</p>
                    <div class="admin-action-card__footer">
                        <span>{{ __('Acessar') }}</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
