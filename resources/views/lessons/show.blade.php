@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Hearts Display -->
<div class="bg-white rounded-lg shadow p-3 mb-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="text-sm font-medium">❤️ Hearts:</span>
            <div class="flex gap-1" id="hearts-display">
                @for($i = 1; $i <= Auth::user()->hearts->current_hearts; $i++)
                    <span class="text-red-500">❤️</span>
                @endfor
                @for($i = Auth::user()->hearts->current_hearts + 1; $i <= 5; $i++)
                    <span class="text-gray-300">🖤</span>
                @endfor
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-sm font-medium">🔥 Streak:</span>
            <span class="text-orange-600 font-bold">{{ Auth::user()->streak->current_streak }} hari</span>
        </div>
    </div>
</div>
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header -->
        <div class="border-b pb-4 mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-gray-500 text-sm">Modul #{{ $lesson->order_number }}</span>
                    <h1 class="text-2xl font-bold mt-1">{{ $lesson->title }}</h1>
                </div>
                @if($isCompleted)
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">✓ Selesai</span>
                @endif
            </div>
        </div>
        
        <!-- Content -->
        <div class="prose max-w-none mb-8">
            <p class="text-gray-700 leading-relaxed">{{ $lesson->content }}</p>
        </div>
        
        <!-- Quiz Section -->
        @if($quizzes->count() > 0 && !$isCompleted)
            <div class="border-t pt-6">
                <h2 class="text-xl font-bold mb-4">📝 Kuis Pemahaman</h2>
                
                <div id="quiz-container">
                    @foreach($quizzes as $index => $quiz)
                        <div class="quiz-item mb-6 p-4 bg-gray-50 rounded-lg" data-quiz-id="{{ $quiz->id }}" data-correct="{{ $quiz->correct_answer }}">
                            <p class="font-medium mb-3">{{ $index + 1 }}. {{ $quiz->question }}</p>
                            <div class="space-y-2">
                                @php
                                    $labels = ['A', 'B', 'C', 'D'];
                                @endphp
                                @foreach($quiz->options as $key => $option)
                                    <label class="flex items-center p-2 rounded cursor-pointer hover:bg-gray-100">
                                        <input type="radio" 
                                               name="quiz_{{ $quiz->id }}" 
                                               value="{{ $key }}" 
                                               class="quiz-radio mr-3"
                                               data-quiz-id="{{ $quiz->id }}">
                                        <span class="font-medium mr-2">{{ $labels[$key] }}.</span>
                                        <span>{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="quiz-feedback mt-2 text-sm hidden"></div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6 flex justify-between items-center">
                    <div id="quiz-status" class="text-gray-600"></div>
                    <button id="complete-lesson-btn" 
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition disabled:opacity-50"
                            data-lesson-id="{{ $lesson->id }}"
                            disabled>
                        Selesaikan Modul & Dapatkan EXP
                    </button>
                </div>
            </div>
        @endif
        
        @if($isCompleted)
            <div class="text-center p-6 bg-green-50 rounded-lg">
                <span class="text-green-600 text-2xl">🎉</span>
                <p class="text-green-700 font-medium mt-2">Modul sudah selesai!</p>
                <p class="text-green-600 text-sm">Kamu sudah mendapatkan +{{ $lesson->exp_reward }} EXP</p>
                <a href="{{ route('dashboard') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Kembali ke Dashboard</a>
            </div>
        @endif
        
        @if(!$isCompleted && $quizzes->count() == 0)
            <div class="text-center p-6">
                <button id="complete-lesson-btn" 
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition"
                        data-lesson-id="{{ $lesson->id }}">
                    Selesaikan Modul & Dapatkan +{{ $lesson->exp_reward }} EXP
                </button>
            </div>
        @endif
    </div>
</div>

<script>
    // Track jawaban yang sudah dijawab
    let answeredQuizzes = new Set();
    let totalQuizzes = {{ $quizzes->count() }};
    let currentHearts = {{ Auth::user()->hearts->current_hearts }};
    
    // Event listener untuk radio button
    document.querySelectorAll('.quiz-radio').forEach(function(radio) {
        radio.addEventListener('change', async function() {
            // Cek apakah user masih punya hearts
            if (currentHearts === 0) {
                alert('❌ Hearts habis! Tidak bisa menjawab soal. Tunggu recharge atau beli hearts di shop.');
                // Uncheck radio
                this.checked = false;
                return;
            }
            
            const quizId = this.dataset.quizId;
            const answer = this.value;
            const quizItem = this.closest('.quiz-item');
            const feedbackDiv = quizItem.querySelector('.quiz-feedback');
            
            // Disable semua radio di quiz ini
            quizItem.querySelectorAll('.quiz-radio').forEach(function(r) {
                r.disabled = true;
            });
            
            // Submit jawaban
            try {
                const response = await fetch('/quiz/' + quizId + '/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ answer: answer })
                });
                
                const data = await response.json();
                
                // Update hearts display
                currentHearts = data.hearts;
                updateHeartsDisplay();
                
                // Tampilkan feedback
                feedbackDiv.innerHTML = data.message;
                feedbackDiv.classList.remove('hidden');
                
                if (data.is_correct) {
                    feedbackDiv.classList.add('text-green-600');
                    if (data.reason) {
                        feedbackDiv.innerHTML = feedbackDiv.innerHTML + '<div class="text-sm text-gray-600 mt-1">Penjelasan: ' + data.reason + '</div>';
                    }
                } else {
                    feedbackDiv.classList.add('text-red-600');
                    feedbackDiv.innerHTML = feedbackDiv.innerHTML + '<div class="text-sm text-gray-600 mt-1">Jawaban benar: ' + data.correct_answer_text + '</div>';
                    if (data.reason) {
                        feedbackDiv.innerHTML = feedbackDiv.innerHTML + '<div class="text-sm text-gray-600 mt-1">Penjelasan: ' + data.reason + '</div>';
                    }
                }
                
                // Track bahwa quiz ini sudah dijawab (benar atau salah)
                answeredQuizzes.add(quizId);
                
                // Cek apakah semua quiz sudah dijawab
                if (answeredQuizzes.size === totalQuizzes) {
                    document.getElementById('complete-lesson-btn').disabled = false;
                    document.getElementById('quiz-status').innerHTML = '✅ Semua kuis sudah dijawab!';
                } else {
                    document.getElementById('quiz-status').innerHTML = '📊 Progress: ' + answeredQuizzes.size + ' / ' + totalQuizzes + ' kuis dijawab';
                }
                
            } catch (error) {
                console.error('Error:', error);
                feedbackDiv.innerHTML = 'Terjadi kesalahan. Silakan coba lagi.';
                feedbackDiv.classList.remove('hidden');
                feedbackDiv.classList.add('text-red-600');
                // Re-enable radio buttons jika error
                quizItem.querySelectorAll('.quiz-radio').forEach(function(r) {
                    r.disabled = false;
                });
            }
        });
    });
    
    // Function to update hearts display
    function updateHeartsDisplay() {
        const heartsDisplay = document.getElementById('hearts-display');
        if (heartsDisplay) {
            let html = '';
            for (let i = 1; i <= currentHearts; i++) {
                html += '<span class="text-red-500">❤️</span>';
            }
            for (let i = currentHearts + 1; i <= 5; i++) {
                html += '<span class="text-gray-300">🖤</span>';
            }
            heartsDisplay.innerHTML = html;
        }
    }
    
    // Complete lesson button
    var completeBtn = document.getElementById('complete-lesson-btn');
    if (completeBtn) {
        completeBtn.addEventListener('click', async function() {
            const lessonId = this.dataset.lessonId;
            const btn = this;
            
            btn.disabled = true;
            btn.innerHTML = 'Memproses...';
            
            try {
                const response = await fetch('/lesson/' + lessonId + '/complete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                console.log("[v0] Response status:", response.status);
                const data = await response.json();
                console.log("[v0] Response data:", data);
                
                if (data.success) {
                    console.log("[v0] Lesson completed successfully");
                    
                    // Redirect to dashboard after 1 second
                    setTimeout(function() {
                        window.location.href = '{{ route("dashboard") }}';
                    }, 1000);
                } else {
                    console.error("[v0] Completion failed:", data.message);
                    alert('Error: ' + data.message);
                    btn.disabled = false;
                    btn.innerHTML = 'Selesaikan Modul & Dapatkan EXP';
                }
            } catch (error) {
                console.error("[v0] Error during completion:", error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                btn.disabled = false;
                btn.innerHTML = 'Selesaikan Modul & Dapatkan EXP';
            }
        });
    }
</script>
@endsection
