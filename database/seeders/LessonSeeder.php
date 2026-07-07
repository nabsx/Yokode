<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        // Lesson 1 - Gratis
        $lesson1 = Lesson::create([
            'title' => 'Apa itu Algoritma?',
            'content' => 'Algoritma adalah langkah-langkah logis untuk menyelesaikan masalah...',
            'exp_reward' => 50,
            'is_premium' => false,
            'order_number' => 1,
        ]);
        
        Quiz::create([
            'lesson_id' => $lesson1->id,
            'question' => 'Apa definisi algoritma yang paling tepat?',
            'options' => ['Sebuah bahasa pemrograman', 'Langkah-langkah logis menyelesaikan masalah', 'Sebuah perangkat keras komputer', 'Aplikasi untuk menulis kode'],
            'correct_answer' => 'B',
            'points' => 10,
        ]);
        
        // Lesson 2 - Premium
        $lesson2 = Lesson::create([
            'title' => 'Pengertian Variable',
            'content' => 'Variable adalah tempat menyimpan data di dalam program...',
            'exp_reward' => 75,
            'is_premium' => true,
            'order_number' => 2,
        ]);
        
        Quiz::create([
            'lesson_id' => $lesson2->id,
            'question' => 'Apa fungsi dari variable?',
            'options' => ['Mencetak teks ke layar', 'Menyimpan data sementara', 'Mengulang perintah', 'Menjalankan fungsi'],
            'correct_answer' => 'B',
            'points' => 10,
        ]);
    }
}