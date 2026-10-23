@props([
    'title',
    'subtitle' => null,
    'label' => 'COMUNICAÇÃO OFICIAL',
])

<table
    role="presentation"
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="margin-bottom: 28px;"
>
    <tr>
        <td>
            <p style="
                margin: 0 0 6px;
                font-size: 11px;
                font-weight: bold;
                letter-spacing: 1px;
                color: #007e85;
            ">
                {{ $label }}
            </p>

            <h1 style="
                margin: 0 0 8px;
                font-size: 26px;
                line-height: 1.3;
                color: #12252d;
            ">
                {{ $title }}
            </h1>

            @if ($subtitle)
                <p style="
                    margin: 0;
                    font-size: 13px;
                    color: #6b7280;
                ">
                    {{ $subtitle }}
                </p>
            @endif
        </td>
    </tr>
</table>
