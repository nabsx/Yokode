<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class CategoryLessonSeeder extends Seeder
{
    public function run(): void
    {
        // ========================================
        // 1. ALGORITMA & LOGIKA
        // ========================================
        $algoritma = Category::create([
            'name' => 'Algoritma & Logika',
            'slug' => 'algoritma-logika',
            'description' => 'Pelajari dasar-dasar algoritma dan logika pemrograman.',
            'icon' => '🧩',
            'color' => 'blue',
            'order' => 1,
            'is_active' => true,
        ]);

        $l1 = Lesson::create([
            'title' => 'Apa itu Algoritma?',
            'content' => 'Algoritma adalah langkah-langkah logis untuk menyelesaikan masalah. Contoh: 1. Bangun tidur 2. Mandi 3. Sarapan 4. Berangkat sekolah.',
            'exp_reward' => 50,
            'is_premium' => false,
            'order_number' => 1,
            'category_id' => $algoritma->id,
        ]);
        Quiz::create([
            'lesson_id' => $l1->id,
            'question' => 'Apa definisi algoritma?',
            'options' => (['Bahasa pemrograman', 'Langkah logis', 'Hardware', 'Software']),
            'correct_answer' => 'B',
            'points' => 10,
        ]);

        $l2 = Lesson::create([
            'title' => 'Flowchart',
            'content' => 'Flowchart adalah diagram alir dengan simbol: Terminal, Proses, Decision, Panah.',
            'exp_reward' => 60,
            'is_premium' => false,
            'order_number' => 2,
            'category_id' => $algoritma->id,
        ]);
        Quiz::create([
            'lesson_id' => $l2->id,
            'question' => 'Simbol percabangan di flowchart?',
            'options' => (['Persegi', 'Belah ketupat', 'Lingkaran', 'Jajar genjang']),
            'correct_answer' => 'B',
            'points' => 10,
        ]);

        $l3 = Lesson::create([
            'title' => 'Pseudocode [PREMIUM]',
            'content' => 'Pseudocode adalah cara menulis algoritma mirip kode. Contoh: PROGRAM HitungLuas INPUT sisi luas = sisi*sisi OUTPUT luas END',
            'exp_reward' => 100,
            'is_premium' => true,
            'order_number' => 3,
            'category_id' => $algoritma->id,
        ]);
        Quiz::create([
            'lesson_id' => $l3->id,
            'question' => 'Apa itu pseudocode?',
            'options' => (['Kode jadi', 'Mirip kode', 'Bahasa khusus', 'Simbol']),
            'correct_answer' => 'B',
            'points' => 15,
        ]);

        // ========================================
        // 2. PYTHON
        // ========================================
        $python = Category::create([
            'name' => 'Python Programming',
            'slug' => 'python',
            'description' => 'Belajar Python dari dasar.',
            'icon' => '🐍',
            'color' => 'green',
            'order' => 2,
            'is_active' => true,
        ]);

        $l4 = Lesson::create([
            'title' => 'Pengenalan Python',
            'content' => 'Python dibuat oleh Guido van Rossum tahun 1991. Sintaks sederhana, banyak library. print("Hello World!")',
            'exp_reward' => 50,
            'is_premium' => false,
            'order_number' => 1,
            'category_id' => $python->id,
        ]);
        Quiz::create([
            'lesson_id' => $l4->id,
            'question' => 'Pencipta Python?',
            'options' => (['Guido', 'Linus', 'Bill', 'Mark']),
            'correct_answer' => 'A',
            'points' => 10,
        ]);

        $l5 = Lesson::create([
            'title' => 'Variabel & Tipe Data',
            'content' => 'Tipe data: int (angka), float (desimal), str (teks), bool (True/False). Contoh: nama="Budi" umur=17',
            'exp_reward' => 60,
            'is_premium' => false,
            'order_number' => 2,
            'category_id' => $python->id,
        ]);
        Quiz::create([
            'lesson_id' => $l5->id,
            'question' => 'Tipe data untuk teks?',
            'options' => (['int', 'float', 'str', 'bool']),
            'correct_answer' => 'C',
            'points' => 10,
        ]);

        $l6 = Lesson::create([
            'title' => 'Percabangan & Perulangan [PREMIUM]',
            'content' => 'IF-ELSE: if nilai>=90: grade A. FOR: for i in range(5): print(i). WHILE: while counter<5.',
            'exp_reward' => 100,
            'is_premium' => true,
            'order_number' => 3,
            'category_id' => $python->id,
        ]);
        Quiz::create([
            'lesson_id' => $l6->id,
            'question' => 'Output for i in range(3): print(i)',
            'options' => (['1 2 3', '0 1 2', '1 2 3 4', '0 1 2 3']),
            'correct_answer' => 'B',
            'points' => 15,
        ]);

        // ========================================
        // 3. HTML
        // ========================================
        $html = Category::create([
            'name' => 'HTML Dasar',
            'slug' => 'html-dasar',
            'description' => 'Belajar struktur dasar website.',
            'icon' => '🌐',
            'color' => 'orange',
            'order' => 3,
            'is_active' => true,
        ]);

        $l7 = Lesson::create([
            'title' => 'Struktur Dasar HTML',
            'content' => 'HTML pakai tag. Struktur: <html><head><title>Judul</title></head><body><h1>Hello</h1><p>Paragraf</p></body></html>',
            'exp_reward' => 50,
            'is_premium' => false,
            'order_number' => 1,
            'category_id' => $html->id,
        ]);
        Quiz::create([
            'lesson_id' => $l7->id,
            'question' => 'Tag untuk konten utama?',
            'options' => (['<head>', '<body>', '<title>', '<html>']),
            'correct_answer' => 'B',
            'points' => 10,
        ]);

        $l8 = Lesson::create([
            'title' => 'Form & Tabel [PREMIUM]',
            'content' => 'TABEL: <table><tr><th>Nama</th></tr><tr><td>Budi</td></tr></table> FORM: <form><input type="text"><button>Kirim</button></form>',
            'exp_reward' => 90,
            'is_premium' => true,
            'order_number' => 2,
            'category_id' => $html->id,
        ]);
        Quiz::create([
            'lesson_id' => $l8->id,
            'question' => 'Tag untuk form?',
            'options' => (['<input>', '<form>', '<table>', '<div>']),
            'correct_answer' => 'B',
            'points' => 15,
        ]);

        // ========================================
        // 4. CSS
        // ========================================
        $css = Category::create([
            'name' => 'CSS Dasar',
            'slug' => 'css-dasar',
            'description' => 'Belajar styling website.',
            'icon' => '🎨',
            'color' => 'pink',
            'order' => 4,
            'is_active' => true,
        ]);

        $l9 = Lesson::create([
            'title' => 'Pengenalan CSS',
            'content' => 'CSS mempercantik website. Cara: Inline, Internal, External. Contoh: body{background:#f0f0f0} h1{color:blue}',
            'exp_reward' => 50,
            'is_premium' => false,
            'order_number' => 1,
            'category_id' => $css->id,
        ]);
        Quiz::create([
            'lesson_id' => $l9->id,
            'question' => 'Fungsi CSS?',
            'options' => (['Struktur', 'Mempercantik', 'Interaksi', 'Data']),
            'correct_answer' => 'B',
            'points' => 10,
        ]);

        $l10 = Lesson::create([
            'title' => 'Flexbox & Grid [PREMIUM]',
            'content' => 'FLEXBOX: display:flex; justify-content:center. GRID: display:grid; grid-template-columns:1fr 1fr 1fr. MEDIA QUERY: @media(max-width:768px)',
            'exp_reward' => 100,
            'is_premium' => true,
            'order_number' => 2,
            'category_id' => $css->id,
        ]);
        Quiz::create([
            'lesson_id' => $l10->id,
            'question' => 'Fungsi display:flex?',
            'options' => (['Layout fleksibel', 'Animasi', 'Warna', 'Sembunyi']),
            'correct_answer' => 'A',
            'points' => 15,
        ]);

        // ========================================
        // 5. JAVASCRIPT
        // ========================================
        $js = Category::create([
            'name' => 'JavaScript Dasar',
            'slug' => 'javascript-dasar',
            'description' => 'Belajar JavaScript untuk web interaktif.',
            'icon' => '📜',
            'color' => 'yellow',
            'order' => 5,
            'is_active' => true,
        ]);

        $l11 = Lesson::create([
            'title' => 'Dasar JavaScript',
            'content' => 'JavaScript membuat website interaktif. alert("Halo") document.getElementById("demo").innerHTML = "Hello" button.onclick = function(){console.log("klik")}',
            'exp_reward' => 50,
            'is_premium' => false,
            'order_number' => 1,
            'category_id' => $js->id,
        ]);
        Quiz::create([
            'lesson_id' => $l11->id,
            'question' => 'Fungsi JavaScript?',
            'options' => (['Struktur HTML', 'Interaktif', 'Tampilan', 'Data']),
            'correct_answer' => 'B',
            'points' => 10,
        ]);

        $l12 = Lesson::create([
            'title' => 'DOM & Event [PREMIUM]',
            'content' => 'DOM MANIPULATION: document.getElementById("judul").textContent="Baru" document.createElement("div"). EVENT: button.addEventListener("click", function(){alert("klik")})',
            'exp_reward' => 100,
            'is_premium' => true,
            'order_number' => 2,
            'category_id' => $js->id,
        ]);
        Quiz::create([
            'lesson_id' => $l12->id,
            'question' => 'Method event listener?',
            'options' => (['addEventListener()', 'onEvent()', 'listenEvent()', 'attachEvent()']),
            'correct_answer' => 'A',
            'points' => 15,
        ]);

        // ========================================
        // 6. DATABASE [PREMIUM]
        // ========================================
        $database = Category::create([
            'name' => 'Database & SQL',
            'slug' => 'database-sql',
            'description' => 'Belajar database dan SQL.',
            'icon' => '🗄️',
            'color' => 'red',
            'order' => 6,
            'is_active' => true,
        ]);

        $l13 = Lesson::create([
            'title' => 'Pengenalan Database [PREMIUM]',
            'content' => 'SQL: CREATE TABLE users(id INT, name VARCHAR) INSERT INTO users VALUES("Budi",17) SELECT * FROM users WHERE age>15 UPDATE users SET age=18 WHERE name="Budi" DELETE FROM users WHERE id=1',
            'exp_reward' => 120,
            'is_premium' => true,
            'order_number' => 1,
            'category_id' => $database->id,
        ]);
        Quiz::create([
            'lesson_id' => $l13->id,
            'question' => 'Singkatan SQL?',
            'options' => (['Structured Query Language', 'Simple Query', 'System Query', 'Sequential Query']),
            'correct_answer' => 'A',
            'points' => 15,
        ]);

        // ========================================
        // 7. LARAVEL [PREMIUM]
        // ========================================
        $laravel = Category::create([
            'name' => 'Laravel Framework',
            'slug' => 'laravel',
            'description' => 'Belajar framework PHP paling populer.',
            'icon' => '⚡',
            'color' => 'red',
            'order' => 7,
            'is_active' => true,
        ]);

        $l14 = Lesson::create([
            'title' => 'Pengenalan Laravel [PREMIUM]',
            'content' => 'Laravel adalah framework PHP MVC. Fitur: Routing, Eloquent ORM, Blade, Authentication, Artisan CLI, Migration. composer create-project laravel/laravel project-name php artisan serve',
            'exp_reward' => 130,
            'is_premium' => true,
            'order_number' => 1,
            'category_id' => $laravel->id,
        ]);
        Quiz::create([
            'lesson_id' => $l14->id,
            'question' => 'Kepanjangan MVC?',
            'options' => (['Model-View-Controller', 'Module-View-Controller', 'Model-Validation-Controller', 'Main-View-Control']),
            'correct_answer' => 'A',
            'points' => 15,
        ]);

        $this->command->info('✅ All categories and lessons seeded successfully!');
        $this->command->info('📚 Total: 7 categories, 14 lessons');
    }
}