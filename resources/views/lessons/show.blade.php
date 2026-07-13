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
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium">🔥 Streak:</span>
                <span class="text-orange-600 font-bold">{{ Auth::user()->streak->current_streak }} hari</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium" id="coins-display">💰 {{ Auth::user()->coins }} Coin</span>
            </div>
        </div>
    </div>
</div>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Banner Image -->
        @if($lesson->banner_image)
            <div class="w-full h-64 overflow-hidden bg-gray-200">
                <img src="{{ $lesson->banner_image }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-6">
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
                    @foreach($quizzesWithStatus as $index => $quiz)
                        {{-- Check if quiz is locked (answered wrong and viewed reason) --}}
                        @if($quiz['status'] === 'locked')
                            <div class="quiz-item mb-6 p-4 bg-red-50 rounded-lg border-2 border-red-300" data-quiz-id="{{ $quiz['id'] }}" data-correct="{{ $quiz['correct_answer'] }}" data-answered="true">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="font-medium">{{ $index + 1 }}. {{ $quiz['question'] }}</p>
                                    <span class="bg-red-200 text-red-700 px-3 py-1 rounded-full text-xs font-medium">🔒 TERKUNCI</span>
                                </div>
                                <div class="bg-red-100 p-3 rounded text-red-700 text-sm mb-3">
                                    <p class="font-medium">⚠️ Soal ini sudah terkunci!</p>
                                    <p class="text-sm mt-1">Anda sudah melihat jawaban untuk soal ini sebelumnya. Tidak bisa mencoba lagi.</p>
                                    @if($quiz['user_answer'])
                                        <p class="mt-2 text-xs text-gray-600">
                                            <strong>Jawaban Anda:</strong> {{ ['A', 'B', 'C', 'D'][$quiz['user_answer']->answer] ?? 'N/A' }}<br>
                                            <strong>Jawaban Benar:</strong> {{ ['A', 'B', 'C', 'D'][$quiz['correct_answer']] ?? 'N/A' }}
                                        </p>
                                        @if($quiz['reason'])
                                            <p class="mt-2 text-xs bg-white p-2 rounded text-gray-700">
                                                <strong>Penjelasan:</strong> {{ $quiz['reason'] }}
                                            </p>
                                        @endif
                                    @endif
                                </div>
                                <div class="space-y-2 opacity-50 pointer-events-none">
                                    @php
                                        $labels = ['A', 'B', 'C', 'D'];
                                    @endphp
                                    @foreach($quiz['options'] as $key => $option)
                                        <label class="flex items-center p-2 rounded bg-gray-100">
                                            <input type="radio" 
                                                   name="quiz_{{ $quiz['id'] }}" 
                                                   value="{{ $key }}" 
                                                   class="quiz-radio mr-3"
                                                   disabled>
                                            <span class="font-medium mr-2">{{ $labels[$key] }}.</span>
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="quiz-item mb-6 p-4 bg-gray-50 rounded-lg" data-quiz-id="{{ $quiz['id'] }}" data-correct="{{ $quiz['correct_answer'] }}" data-answered="{{ $quiz['user_answer'] ? 'true' : 'false' }}">
                                <p class="font-medium mb-3">{{ $index + 1 }}. {{ $quiz['question'] }}</p>
                                <div class="space-y-2">
                                    @php
                                        $labels = ['A', 'B', 'C', 'D'];
                                    @endphp
                                    @foreach($quiz['options'] as $key => $option)
                                        <label class="flex items-center p-2 rounded cursor-pointer hover:bg-gray-100">
                                            <input type="radio" 
                                                   name="quiz_{{ $quiz['id'] }}" 
                                                   value="{{ $key }}" 
                                                   class="quiz-radio mr-3"
                                                   data-quiz-id="{{ $quiz['id'] }}"
                                                   {{ $quiz['status'] === 'completed' ? 'disabled' : '' }}>
                                            <span class="font-medium mr-2">{{ $labels[$key] }}.</span>
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="quiz-feedback mt-2 text-sm hidden"></div>
                                @if($quiz['status'] === 'completed' && $quiz['user_answer'])
                                    <div class="mt-3 p-3 bg-green-100 rounded text-green-700 text-sm">
                                        <p class="font-medium">✅ Sudah dijawab dengan benar</p>
                                    </div>
                                @endif
                            </div>
                        @endif
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
</div>

<script>
    // Track jawaban yang sudah dijawab
    let answeredQuizzes = new Set();
    
    // ANTI-CHEAT: Hitung hanya quiz yang bisa dijawab (exclude locked ones)
    let totalQuizzes = 0;
    let lockedQuizzes = new Set();
    document.querySelectorAll('.quiz-item').forEach(function(item) {
        const quizId = item.dataset.quizId;
        // Cek apakah ada warning "TERKUNCI" di item ini
        const isLocked = item.querySelector('.bg-red-50') !== null || 
                         item.querySelector('[class*="TERKUNCI"]') !== null;
        if (isLocked) {
            lockedQuizzes.add(quizId);
            // Locked quiz yang sudah dijawab juga ditambah ke answered set
            answeredQuizzes.add(quizId);
        } else {
            totalQuizzes++;
            // Jika quiz sudah dijawab (dari backend), tambahkan ke answered set
            if (item.dataset.answered === 'true') {
                answeredQuizzes.add(quizId);
            }
        }
    });
    
    let currentHearts = {{ Auth::user()->hearts->current_hearts }};
    
    // Check button enable status setiap kali page load
    function updateCompleteButtonStatus() {
        if (answeredQuizzes.size === totalQuizzes && totalQuizzes > 0) {
            document.getElementById('complete-lesson-btn').disabled = false;
            if (lockedQuizzes.size > 0) {
                document.getElementById('quiz-status').innerHTML = '✅ Semua kuis sudah dijawab! (' + lockedQuizzes.size + ' soal terkunci)';
            } else {
                document.getElementById('quiz-status').innerHTML = '✅ Semua kuis sudah dijawab!';
            }
        } else {
            let remaining = totalQuizzes - answeredQuizzes.size;
            document.getElementById('quiz-status').innerHTML = '📊 Progress: ' + answeredQuizzes.size + ' / ' + totalQuizzes + ' kuis dijawab (' + remaining + ' tersisa)';
        }
    }
    
    // Call on page load
    document.addEventListener('DOMContentLoaded', updateCompleteButtonStatus);
    
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
                
                // Update coins display if available
                if (data.total_coins !== undefined) {
                    updateCoinsDisplay(data.total_coins);
                }
                
                // ANTI-CHEAT: Handle locked quiz
                if (data.is_locked) {
                    feedbackDiv.innerHTML = data.message;
                    feedbackDiv.classList.remove('hidden');
                    feedbackDiv.classList.add('text-red-600');
                    alert('⚠️ Quiz sudah terkunci! Anda tidak bisa mencoba lagi karena sudah melihat jawabannya.');
                    return;
                }
                
                // Tampilkan feedback
                feedbackDiv.innerHTML = data.message;
                feedbackDiv.classList.remove('hidden');
                
                if (data.is_correct) {
                    feedbackDiv.classList.add('text-green-600');
                    // COIN CONVERSION: Tampilkan coins yang diperoleh
                    if (data.coins_earned > 0) {
                        feedbackDiv.innerHTML = feedbackDiv.innerHTML + '<div class="text-sm text-yellow-600 font-medium mt-2">💰 +' + data.coins_earned + ' coin (dari ' + data.points + ' poin)</div>';
                    }
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
                
                // Update button status
                updateCompleteButtonStatus();
                
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
    
    // Function to update coins display
    function updateCoinsDisplay(totalCoins) {
        const coinsDisplay = document.getElementById('coins-display');
        if (coinsDisplay) {
            coinsDisplay.innerHTML = '💰 ' + totalCoins + ' Coin';
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
