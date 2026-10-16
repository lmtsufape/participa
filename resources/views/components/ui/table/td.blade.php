@once
    <style>
        .app-table-td {
            padding: 1.15rem 1rem !important;
            border-top: 0 !important;
            border-bottom: 1px solid #edf0f2 !important;
            vertical-align: middle;
        }

        .app-table tbody tr:last-child .app-table-td {
            border-bottom: 0 !important;
        }
    </style>
@endonce

<td {{ $attributes->class(['app-table-td']) }}>
    {{ $slot }}
</td>
