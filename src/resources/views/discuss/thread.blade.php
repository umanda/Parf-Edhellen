@extends('_layouts.default')

@section('title', $thread->subject.' - Discussion')
@section('body')
  <article>
    <nav class="discuss-breadcrumbs">
      {!! Breadcrumbs::render('discuss.show', $group, $thread) !!}
    </nav>
    <header>
      <h1>{{ $thread->subject }}</h1>
    </header>
    <section class="discuss-entity">
    {!! $context->view($thread->entity) !!}
    </section>
    <aside id="discuss-toolbar" data-thread-id="{{ $thread->id }}"></aside>
    <section class="discuss-body">
      @foreach ($preloadedPosts['posts'] as $post)
        @include('discuss._post', $post)
      @endforeach
      @include('discuss._pagination', $preloadedPosts)
      <pre>{{ json_encode([$preloadedPosts], JSON_PRETTY_PRINT) }}</pre>
      @include('_shared._comments', [
        'entity_id' => $thread->entity_id,
        'morph'     => $thread->entity_type,
        'enabled'   => true,
        'order'     => 'asc'
      ])
    </section>
  </article>
@endsection
@section('styles')
<link rel="stylesheet" href="@assetpath(style-discuss.css)">
@endsection
@section('scripts')
@endsection
