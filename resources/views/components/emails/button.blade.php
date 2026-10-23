@props([
    'href',
])

<table
    role="presentation"
    cellpadding="0"
    cellspacing="0"
    border="0"
    align="center"
    style="margin: 24px auto;"
>
    <tr>
        <td
            align="center"
            bgcolor="#00454d"
            style="border-radius: 6px;"
        >
            <a
                href="{{ $href }}"
                target="_blank"
                style="
                    display: inline-block;
                    padding: 12px 28px;
                    background-color: #00454d;
                    color: #ffffff;
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 15px;
                    font-weight: 600;
                    line-height: 1.4;
                    text-align: center;
                    text-decoration: none;
                    border-radius: 6px;
                "
            >
                {{ $slot }}
            </a>
        </td>
    </tr>
</table>
