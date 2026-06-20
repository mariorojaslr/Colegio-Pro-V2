@php
    $schoolId = null;
    if(auth()->check() && auth()->user()->school_id) {
        $schoolId = auth()->user()->school_id;
    } elseif(app()->bound('tenant') && app('tenant')) {
        $schoolId = app('tenant')->id;
    }
    
    $banner = null;
    if($schoolId) {
        $banner = \App\Models\EventBanner::where('school_id', $schoolId)->activeEvent()->first();
    }
@endphp

@if($banner)
<div class="modal fade" id="promo-flyer-modal-{{ $banner->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-banner-id="{{ $banner->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg bg-transparent">
            <div class="modal-header border-0 position-absolute w-100 p-3" style="z-index: 10;">
                <button type="button" class="btn-close btn-close-white shadow-sm bg-dark bg-opacity-50 rounded-circle p-2 ms-auto" data-bs-dismiss="modal" aria-label="Close" title="Cerrar por hoy"></button>
            </div>
            <div class="modal-body p-0 position-relative overflow-hidden rounded-4 text-center bg-dark">
                @php
                    $imgSrc = Str::startsWith($banner->image_path, ['http://', 'https://']) 
                        ? $banner->image_path 
                        : asset('storage/'.$banner->image_path);
                @endphp
                @if($banner->link_url)
                    <a href="{{ $banner->link_url }}" target="_blank">
                        <img src="{{ $imgSrc }}" alt="{{ $banner->title }}" class="img-fluid w-100" style="max-height: 80vh; object-fit: contain;">
                    </a>
                @else
                    <img src="{{ $imgSrc }}" alt="{{ $banner->title }}" class="img-fluid w-100" style="max-height: 80vh; object-fit: contain;">
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bannerElement = document.getElementById('promo-flyer-modal-{{ $banner->id }}');
    if (bannerElement) {
        const bannerId = bannerElement.dataset.bannerId;
        const storageKey = 'flyer_seen_' + bannerId;
        
        // Obtenemos la fecha de hoy en formato local YYYY-MM-DD
        const today = new Date().toLocaleDateString('en-CA'); 
        const lastSeen = localStorage.getItem(storageKey);
        
        // Si no lo ha visto hoy, mostramos el modal
        if (lastSeen !== today) {
            // Esperar un segundo para que sea menos intrusivo de golpe
            setTimeout(() => {
                const modal = new bootstrap.Modal(bannerElement);
                modal.show();
                
                // Al cerrar, lo marcamos como visto hoy
                bannerElement.addEventListener('hidden.bs.modal', function () {
                    localStorage.setItem(storageKey, today);
                });
            }, 1000);
        }
    }
});
</script>
@endif
