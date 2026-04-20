<x-app-layout>

<div class="p-10 text-center">

<h1 class="text-3xl font-bold mb-4">
Quiz Completed
</h1>

<p class="text-lg text-gray-600">
{{ $quiz->title }}
</p>

<div class="mt-6">

<p class="text-xl">Your Score</p>

<h2 class="text-5xl font-bold text-purple-600 mt-2">
{{ $score }}
</h2>

</div>

<a href="{{ route('student.dashboard') }}"
class="mt-8 inline-block bg-purple-600 text-white px-6 py-3 rounded hover:bg-purple-700">

Back to Dashboard

</a>

</div>

</x-app-layout>

