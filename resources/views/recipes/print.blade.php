<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>{{ $recipe->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin: 0 0 6px; }
        .meta { font-size: 11px; color: #555; margin-bottom: 10px; }
        .section { margin: 10px 0; }
        ul { margin: 0; padding-left: 18px; }
        li { margin: 2px 0; }
        hr { border: none; border-top: 1px dashed #ccc; margin: 8px 0; }
    </style>
</head>
<body>
<h1>{{ $recipe->title }}</h1>
<div class="meta">
    Tempo de preparo: {{ $recipe->prep_time }} min · Rendimento: {{ $recipe->yield ?? '—' }}
</div>
@if($recipe->description)
    <div class="section"><strong>Descrição:</strong><br>{{ $recipe->description }}</div>
@endif

@if($recipe->ingredients && is_array($recipe->ingredients))
    <div class="section"><strong>Ingredientes:</strong>
        <ul>
            @foreach($recipe->ingredients as $i)
                <li>{{ $i }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($recipe->steps && is_array($recipe->steps))
    <div class="section"><strong>Modo de preparo:</strong>
        <ol>
            @foreach($recipe->steps as $s)
                <li>{{ $s }}</li>
            @endforeach
        </ol>
    </div>
@endif

@if($recipe->tags && is_array($recipe->tags))
    <hr>
    <div class="meta">Tags: {{ implode(', ', $recipe->tags) }}</div>
@endif
</body>
</html>
