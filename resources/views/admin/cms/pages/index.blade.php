@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Páginas Dinámicas</h2>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#comoUsarModal">
                <i class="bi bi-info-circle me-1"></i> Cómo se usa
            </button>
            <a href="{{ route('admin.cms.pages.create') }}" class="btn btn-primary">Crear Nueva Página</a>
        </div>
    </div>

    <!-- Modal Cómo se usa -->
    <div class="modal fade" id="comoUsarModal" tabindex="-1" aria-labelledby="comoUsarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-warning text-dark border-0">
            <h5 class="modal-title fw-bold" id="comoUsarModalLabel"><i class="bi bi-info-circle me-2"></i>¿Qué son y cómo usar las Páginas Dinámicas?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body p-4 text-dark" style="font-size: 1.05rem; line-height: 1.6;">
            <p>Esta sección es tu <strong>creador de contenido web</strong>. Te permite crear páginas informativas a tu medida que aparecerán automáticamente en tu sitio web público, sin depender de un programador.</p>
            
            <h5 class="fw-bold text-primary mt-4">¿Qué tipo de cosas se hacen aquí?</h5>
            <ul>
                <li><strong>"Historia del Colegio":</strong> Puedes redactar la historia y subir fotos.</li>
                <li><strong>"Leyes y Resoluciones":</strong> Pegar el texto completo de la Ley Provincial o Estatuto para consulta pública.</li>
                <li><strong>"Beneficios y Convenios":</strong> Listar los convenios con obras sociales, hoteles, comercios, etc.</li>
            </ul>

            <h5 class="fw-bold text-primary mt-4">Paso a paso para crear una:</h5>
            <ol>
                <li>Haces clic en el botón azul <strong>"Crear Nueva Página"</strong>.</li>
                <li><strong>Título:</strong> Le pones un nombre (Ej: <em>Estatuto Provincial</em>).</li>
                <li><strong>URL (Extensión):</strong> ¡El sistema lo hace por ti! Si le pones de título "Estatuto Provincial", el sistema automáticamente creará el link web <code>/p/estatuto-provincial</code>.</li>
                <li><strong>Contenido:</strong> Te aparecerá un editor de texto (como Word) donde escribes toda tu información.</li>
                <li>Le das a Guardar, ¡y listo! La página ya estará viva en internet.</li>
            </ol>
            
            <div class="alert alert-info mt-4 mb-0 border-0 shadow-sm">
                <strong>¿Dónde aparece esto?</strong> Una vez creada, cualquier persona podrá ingresar escribiendo el enlace en su navegador. Además, luego podemos crear botones en el menú principal para que la gente simplemente haga clic y las lea.
            </div>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Entendido, cerrar</button>
          </div>
        </div>
      </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>URL Slug</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td><code>/p/{{ $page->slug }}</code></td>
                        <td>
                            @if($page->is_published)
                                <span class="badge bg-success">Publicado</span>
                            @else
                                <span class="badge bg-secondary">Borrador</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.cms.pages.edit', $page->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay páginas creadas aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
