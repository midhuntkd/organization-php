@extends('layouts.inner_page')

@section('page_title', 'Super Admin Dashboard')

@section('content')
<div class="container py-5">
    <h3>Welcome, {{ auth()->user()->name }}</h3>
    <p>You are logged in as <strong>Super Admin</strong>.</p>
</div>
@endsection