<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container mx-auto mt-10">
        <h1 class="text-center text-2xl font-bold">FAQ - Pertanyaan yang Sering Diajukan</h1>
        <div class="mt-5">
            @foreach ($faqs as $faq)
            <div class="mb-4">
                <button class="w-full text-left bg-gray-200 px-4 py-2 font-bold" onclick="toggleFaq({{ $faq->id }})">
                    {{ $faq->pertanyaan }}
                </button>
                <div id="faq-{{ $faq->id }}" class="hidden bg-gray-100 px-4 py-2">
                    {{ $faq->jawaban }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        function toggleFaq(id) {
            const faq = document.getElementById(`faq-${id}`);
            faq.classList.toggle('hidden');
        }
    </script>
</body>
</html>
