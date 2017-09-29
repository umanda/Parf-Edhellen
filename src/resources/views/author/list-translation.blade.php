@inject('link', 'App\Helpers\LinkHelper')
@extends('_layouts.default')

@section('title', 'Glossary by '.$author->nickname)

@section('body')
  <h1>Glossary by {{ $author->nickname }}</h1>
  @if (count($translations))
  <p>These are the {{ $translations->count() }} latest contributions by <a href="{{ $link->author($author->id, $author->nickname) }}">{{ $author->nickname }}</a>.</p>

  <table class="table table-hover table-striped">
    <thead>
      <tr>
        <th>Created</th>
        <th>Language</th>
        <th>Word / sense</th>
        <th>Gloss</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($translations as $translation)
      <tr>
        <td>{{ $translation->created_at->format('Y-m-d H:i') }}</td>
        <td>{{ $translation->language->name }}</td>
        <td><a href="{{ $link->translation($translation->id) }}">{{ $translation->word->word }} / {{ $translation->sense->word->word }}</a></td>
        <td>{{ $translation->translation }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <em>There are no recorded contributions by this account.</em>
  @endif
@endsection
