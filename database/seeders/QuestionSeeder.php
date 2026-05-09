<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/question_batches.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("الملف غير موجود في: $jsonPath");
            return;
        }

        $jsonContent = File::get($jsonPath);
        $batches = json_decode($jsonContent, true);

        if (is_array($batches)) {
            foreach ($batches as $batchKey => $questions) {
                
                // استخراج رقم الـ Batch من الاسم (مثلاً batch_1 تصبح 1)
                $batchNumber = (int) filter_var($batchKey, FILTER_SANITIZE_NUMBER_INT);

                foreach ($questions as $q) {
                    Question::updateOrCreate(
                        // غيرنا question_text لـ question عشان تطابق ملف الـ JSON بتاعك
                        ['question_text' => $q['question']], 
                        [
                            'input_type'    => $q['input_type'],
                            'options'       => !empty($q['options']) ? $q['options'] : null,
                            'batch_number'  => $batchNumber,
                            'min'           => $q['min'] ?? null,
                            'max'           => $q['max'] ?? null,
                        ]
                    );
                }
            }
            $this->command->info('تم رفع الـ 30 سؤال بنجاح من كافة المجموعات!');
        }
    }
}