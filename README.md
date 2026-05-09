# 🎓 EduPRE: AI-Driven Student Performance & Quiz System

EduPRE is a sophisticated web platform designed to evaluate student performance using **Laravel** for the core engine and **Python Machine Learning** for predictive analytics. It also integrates the **Gemini API** for intelligent CV parsing and feedback.

##   Key Features
*   **AI Performance Prediction:** Predicts if a student will (Excellent, Good, Pass, or Fail) based on historical data using CatBoost models (`.cbm`).
*   **Smart Recommendations:** Analyzes student answers against optimal benchmarks (`.pkl` files) to provide personalized learning paths.
*   **AI CV Parsing:** Integrated with **Gemini API** to analyze uploaded resumes for job applications within the platform.
*   **Interactive Dashboard:** A professional UI built with **Tailwind CSS** and **Alpine.js** for real-time performance tracking.
*   **Dockerized Environment:** Fully configured to run via **Docker** and **WSL** for consistent development.

##   Tech Stack
*   **Backend:** PHP 8.4 / Laravel 13.x
*   **AI/ML Logic:** Python 3.x (Pandas, CatBoost)
*   **Frontend:** Blade, Tailwind CSS, Alpine.js
*   **Database:** MariaDB
*   **APIs:** Google Gemini API

##   AI Implementation
The core AI logic is located in the `scripts/` directory:
- `predict_student.py`: The main prediction engine.
- `compare_answers.py`: Generates the recommendation logic.
- `*.pkl` & `*.cbm`: Pre-trained models and encoders.

##   Quick Start
1. **Clone & Install:**
   ```bash
   git clone [https://github.com/Mariammahmoud2/Student-Performance-Prediction-System.git](https://github.com/Mariammahmoud2/Student-Performance-Prediction-System.git)
   composer install
   npm install && npm run dev
