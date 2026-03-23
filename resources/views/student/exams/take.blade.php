@extends('layouts.main')

@section('header', 'Evaluación Final: ' . $exam->title)

@section('content')
<div class="container-fluid px-4 py-5 min-vh-100" style="background: #0f172a;">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            {{-- Header del Examen --}}
            <div class="text-center mb-5">
                <span class="badge bg-primary rounded-pill px-3 py-1 xx-small fw-black ls-2 mb-3">EXAMEN ACADÉMICO PROTEGIDO</span>
                <h1 class="text-white fw-black mb-3" style="font-family: 'Outfit', sans-serif;">{{ $exam->title }}</h1>
                <div class="d-flex justify-content-center gap-4 text-white-50 small fw-bold uppercase ls-1">
                    <div><i class="bi bi-list-check me-2 text-primary"></i> {{ $exam->questions->count() }} PREGUNTAS</div>
                    <div><i class="bi bi-clock me-2 text-primary"></i> {{ $exam->time_limit ?? 'SIN LÍMITE' }} MIN</div>
                    <div><i class="bi bi-patch-check me-2 text-primary"></i> {{ $exam->passing_score }}% PARA APROBAR</div>
                </div>
            </div>

            <form action="{{ route('student.exams.submit', $exam->id) }}" method="POST" id="examForm">
                @csrf
                
                @foreach($exam->questions as $index => $question)
                    <div class="card border-0 shadow-lg mb-5" style="border-radius: 30px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05) !important;">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex align-items-start gap-4 mb-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-black" style="width: 40px; height: 40px; flex-shrink: 0;">
                                    {{ $index + 1 }}
                                </div>
                                <h4 class="text-white fw-medium lh-base m-0">{{ $question->question_text }}</h4>
                            </div>

                            <div class="ps-md-5 d-flex flex-column gap-3">
                                @foreach($question->options as $option)
                                    <label class="option-card p-3 rounded-4 d-flex align-items-center gap-3 cursor-pointer transition-all border border-light border-opacity-10 text-white-50" style="background: rgba(255,255,255,0.01)">
                                        <input type="radio" name="q-{{ $question->id }}" value="{{ $option->id }}" class="form-check-input bg-transparent border-white-50" required>
                                        <span class="fw-medium">{{ $option->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="text-center mt-5 mb-5 pb-5">
                    <button type="button" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-black shadow-lg" onclick="confirmSubmit()">
                        FINALIZAR EVALUACIÓN <i class="bi bi-send ms-2"></i>
                    </button>
                    <p class="text-white-50 mt-3 small">Al presionar finalizar, sus respuestas serán procesadas y calificadas automáticamente.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .xx-small { font-size: 9px; }
    .ls-1 { letter-spacing: 1px; }
    .ls-2 { letter-spacing: 2px; }
    .fw-black { font-weight: 900; }
    .option-card:hover {
        background: rgba(255,255,255,0.05) !important;
        border-color: var(--primary-color) !important;
        color: white !important;
        transform: translateX(10px);
    }
    .option-card input:checked + span {
        color: white !important;
        font-weight: 700;
    }
    .option-card:has(input:checked) {
        background: rgba(var(--primary-rgb), 0.1) !important;
        border-color: var(--primary-color) !important;
        color: white !important;
    }
    .cursor-pointer { cursor: pointer; }
</style>

<script>
    function confirmSubmit() {
        const total = {{ $exam->questions->count() }};
        const answered = document.querySelectorAll('input[type="radio"]:checked').length;
        
        if (answered < total) {
            if (confirm(`Solo has respondido ${answered} de ${total} preguntas. ¿Seguro que deseas continuar?`)) {
                document.getElementById('examForm').submit();
            }
        } else {
            if (confirm('¿Deseas enviar tus respuestas para calificar?')) {
                document.getElementById('examForm').submit();
            }
        }
    }
</script>
@endsection
