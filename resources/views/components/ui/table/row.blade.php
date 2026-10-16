@once
    <style>
        .app-table-row {
            transition: background-color 0.15s ease;
        }

        .app-table-row:hover > td {
            --bs-table-bg: #f8fafc;
        }
    </style>
@endonce

<tr {{ $attributes->class(['app-table-row']) }}>
    {{ $slot }}
</tr>
