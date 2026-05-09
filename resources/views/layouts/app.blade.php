<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduPRE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f3f4f6] font-sans antialiased">

    @include('layouts.navigation')

    <main>
        @yield('content')
    </main>

    <footer class="bg-[#f8f8fb] border-t border-gray-100 pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-10" dir="ltr">
                
                <div>
                    <h4 class="text-gray-900 font-bold mb-4 text-base">About Us</h4>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        We provide modern web solutions with a user-friendly experience.
                    </p>
                </div>

                <div>
                    <h4 class="text-gray-900 font-bold mb-4 text-base">Useful Links</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-[#6366f1] transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-[#6366f1] transition-colors">Terms & Conditions</a></li>
                        <li><a href="#" class="hover:text-[#6366f1] transition-colors">Support Center</a></li>
                        <li><a href="#" class="hover:text-[#6366f1] transition-colors">FAQs</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-gray-900 font-bold mb-4 text-base">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>Address: Sadat City - Menoufia</li>
                        <li>Email: EDUPRE@gmail.com</li>
                        <li>Phone: +20123456789</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 text-center">
                <p class="text-gray-400 text-sm">© 2026 All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>