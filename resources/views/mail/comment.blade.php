<x-mail::message>
# Новый комментарий
Добавлен комментарий с текстом:
<x-mail::panel>
{{$comment->text}}
</x-mail::panel>
Для статьи: {{ $article_title}}. <br>
Автор комментария: {{ $author }}.
<x-mail::button :url="'http://127.0.0.1:3000/comment'">
Модерация комментариев
</x-mail::button>

</x-mail::message>
