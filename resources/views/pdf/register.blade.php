<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: "Noto Sans Bengali", sans-serif; font-size: 11px; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 4px; text-align: left; }
        th { background: #f3efe6; }
    </style>
</head>
<body>
    <h1>{{ $companyBn }}</h1>
    <p>{{ $company }} — {{ $title }}</p>
    <table>
        <thead>
            <tr>
                @foreach ($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ max(count($headings), 1) }}">—</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
