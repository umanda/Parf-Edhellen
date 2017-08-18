@extends('_layouts.default')

@section('title', 'System errors - Administration')
@section('body')

<h1>System errors</h1>
{!! Breadcrumbs::render('system-error.index') !!}

@if (count($errors) < 1)
  <p>
    <em>There are presently no errors registered by the logging service.</em>
  </p>
@else
  <div class="table-responsive">
    <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Date</th>
        <th>URL</th>
        <th>User</th>
      </tr>
    </thead>
    @foreach ($errors as $error)
    <tbody>
      <tr>
        <td>{{ $error->created_at }}</td>
        <td>{{ $error->url }}</td>
        <td>{{ $error->account_id }} ({{ $error->ip }})</td>
      </tr>
      <tr>
        <td colspan="3">
          {{ $error->message }}
          {{ $error->error }}
        </td>
      </tr>
    </tbody>
    @endforeach
    </table>
  </div>
@endif

@endsection