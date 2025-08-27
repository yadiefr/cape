@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/ppdb-single-page.css') }}">
<style>
    body {
        background: linear-gradient(135deg, #e0f2fe 0%, #ede9fe 100%);
        min-height: 100vh;
    }
</style>
@endsection

@section('content')
    @yield('pendaftaran-content')
@endsection
