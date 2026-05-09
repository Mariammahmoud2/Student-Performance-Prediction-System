#!/usr/bin/env python3

import pickle
import json
import sys
from pathlib import Path

def main():
    try:
        # 1. قراءة البيانات القادمة من Laravel (Standard Input)
        raw_input = sys.stdin.read()
        if not raw_input:
            raise ValueError("No input data received")
            
        input_data = json.loads(raw_input)
        student_answers = input_data.get('student_answers', {})
        
        # 2. تحديد مسار ملف الـ pkl (بجوار السكريبت مباشرة)
        current_dir = Path(__file__).parent
        pkl_path = current_dir / 'best_values.pkl'
        
        if not pkl_path.exists():
            print(json.dumps({
                'status': 'error',
                'message': f'File not found: {pkl_path}',
                'recommendations': []
            }, ensure_ascii=False))
            return

        # 3. تحميل الإجابات النموذجية
        with open(pkl_path, 'rb') as f:
            best_values = pickle.load(f)
        
        recommendations = []
        counter = 1
        
        # 4. مقارنة الإجابات
        # نستخدم نص السؤال كمفتاح للبحث في ملف الـ pkl
        for question_text, student_value in student_answers.items():
            optimal_value = best_values.get(question_text)
            
            if optimal_value is None:
                continue
            
            student_str = str(student_value).strip()
            optimal_str = str(optimal_value).strip()
            
            if student_str != optimal_str:
                recommendations.append({
                    'number': counter,
                    'question': question_text,
                    'your_answer': student_str,
                    'recommended_answer': optimal_str,
                    'impact': 'عالي'
                })
                counter += 1
        
        # 5. إرسال النتيجة بصيغة JSON
        print(json.dumps({
            'status': 'success',
            'recommendations': recommendations,
            'total': len(recommendations)
        }, ensure_ascii=False))
        
    except Exception as e:
        print(json.dumps({
            'status': 'error', 
            'message': str(e),
            'recommendations': []
        }, ensure_ascii=False))

if __name__ == '__main__':
    main()