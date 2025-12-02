<x-mail::message>
# Introduction

Количество добавленных комментариев: {{ $countComment }}

@if(!empty($countArticle) && isset($countArticle[0]))
Количество просмотров статей: {{ array_sum(array_column($countArticle, 'count')) }}

Просмотрены следующие статьи:
@foreach($countArticle as $value)
- {{ $value['article_title'] }} ({{ $value['count'] }} просмотров)
@endforeach
@else
Нет просмотренных статей за этот период.
@endif

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>