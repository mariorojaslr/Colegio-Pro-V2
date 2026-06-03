@extends('layouts.main')

@section('title', $page->title . ' | Colegio-Pro')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">{{ $page->title }}</h1>
            <div class="page-content bg-white p-4 rounded shadow-sm">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection
