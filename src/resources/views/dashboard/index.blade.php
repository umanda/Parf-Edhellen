@inject('link', 'App\Helpers\LinkHelper')
@extends('_layouts.default')

@section('title', 'Welcome!')
@section('body')
  <h1>Dashboard</h1>
  
  {!! Breadcrumbs::render('dashboard') !!}

  <div class="alert alert-info">
    <strong>Hi!</strong>
    I just want to let you know that this is a <em>very</em> early version of the dashboard.
    I am still working on it! 
  </div>

  <div class="row">
    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h2 class="panel-title"><span class="glyphicon glyphicon-user"></span> About you</h2>
        </div>
        <div class="panel-body">
          <ul>
            <li><a href="{{ route('author.my-profile') }}">Your profile</a></li>
          </ul>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h2 class="panel-title"><span class="glyphicon glyphicon-globe"></span> Community</h2>
        </div>
        <div class="panel-body">
          <ul>
            <li>
              <a href="{{ route('flashcard') }}">Flashcards</a>
              @if ($noOfFlashcards)
              <span class="badge badge-info">{{ $noOfFlashcards }}</span>
              @endif
            </li>
            <li>
              <a href="{{ route('translation-review.index') }}">Contributions</a>
              @if ($noOfContributions)
              <span class="badge badge-info">{{ $noOfContributions }}</span>
              @endif
            </li>
          </ul>
        </div>
      </div>

    </div>
    <div class="col-md-6">
      @if ($user->isAdministrator())
      <div class="panel panel-default">
        <div class="panel-heading">
          <h2 class="panel-title"><span class="glyphicon glyphicon-cog"></span> Administration</h2>
        </div>
        <div class="panel-body">
          <ul>
            <li><a href="{{ route('speech.index') }}">Type of speeches</a></li>
            <li><a href="{{ route('inflection.index') }}">Inflections</a></li>
            <li><a href="{{ route('translation.index') }}">Words</a></li>
            <li><a href="{{ route('sentence.index') }}">Phrases</a></li>
          </ul>
          <hr>
          <ul>
            <li><a href="{{ route('system-error.index') }}">System errors</a></li>
          </ul>
        </div>
      </div>
      @endif
    </div>

  </div>
@endsection