@once
    <style>
        .app-table-actions {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .app-table-actions .table-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
            color: #495057;
        }

        .app-table-actions .table-action:hover {
            background-color: #f1f3f5;
        }
    </style>
@endonce

<div {{ $attributes->class(['app-table-actions']) }}>
    {{ $slot }}
</div>
