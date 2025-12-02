@extends('layout')
@section('content')

<h1>Редактирование комментария</h1>
<form action="{{ url('/comment/update/' . $comment->id) }}" method="POST"> 
    @csrf
    <div class="mb-3">
        <label for="text" class="form-label">Текст комментария</label>
        <textarea name="text" id="text" class="form-control" rows="5">{{ old('text', $comment->text) }}</textarea>
        @error('text')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Обновить</button>
    <a href="{{ route('article.show', $comment->article_id) }}" class="btn btn-secondary">Отмена</a>
</form>
@endsection