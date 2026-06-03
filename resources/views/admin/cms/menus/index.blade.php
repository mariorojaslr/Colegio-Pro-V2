@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <h2>Gestor de Menús</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Crear Nuevo Menú</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cms.menus.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Nombre del Menú (Uso interno)</label>
                            <input type="text" name="name" class="form-control" required placeholder="Ej: Menú Principal">
                        </div>
                        <div class="mb-3">
                            <label>Ubicación</label>
                            <select name="location" class="form-select">
                                <option value="header">Cabecera (Header)</option>
                                <option value="footer">Pie de página (Footer)</option>
                            </select>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" checked value="1">
                            <label class="form-check-label" for="is_active">Activo</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Crear Menú</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            @foreach($menus as $menu)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $menu->name }} <small class="text-muted">({{ $menu->location }})</small></h5>
                </div>
                <div class="card-body">
                    <h6>Ítems del Menú</h6>
                    <ul class="list-group mb-4">
                        @forelse($menu->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $item->title }}</strong>
                                    @if($item->page_id)
                                        <span class="badge bg-info">Página Dinámica</span>
                                    @else
                                        <span class="badge bg-secondary">URL: {{ $item->url }}</span>
                                    @endif
                                    @if(!$item->is_active)
                                        <span class="badge bg-warning">Oculto</span>
                                    @endif
                                </div>
                                <span class="badge bg-primary rounded-pill">Orden: {{ $item->order }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No hay ítems en este menú.</li>
                        @endforelse
                    </ul>

                    <h6>Añadir nuevo ítem a este menú</h6>
                    <form action="{{ route('admin.cms.menus.items.store', $menu->id) }}" method="POST" class="border p-3 rounded bg-light">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label>Título a mostrar</label>
                                <input type="text" name="title" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label>Enlazar a Página Creada</label>
                                <select name="page_id" class="form-select form-select-sm">
                                    <option value="">-- No enlazar a página --</option>
                                    @foreach($pages as $page)
                                        <option value="{{ $page->id }}">{{ $page->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label>O URL Externa</label>
                                <input type="text" name="url" class="form-control form-control-sm" placeholder="https://...">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label>Orden</label>
                                <input type="number" name="order" class="form-control form-control-sm" value="0">
                            </div>
                            <div class="col-md-4 mb-2 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_active" id="item_active" checked value="1">
                                    <label class="form-check-label" for="item_active">Visible</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-sm btn-success w-100">Añadir Ítem</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
