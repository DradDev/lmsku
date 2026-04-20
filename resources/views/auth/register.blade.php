<x-guest-layout>

<div class="min-h-screen flex items-center justify-center bg-gray-100">

<div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">

<div class="text-center mb-6">

<div class="w-16 h-16 mx-auto bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center text-white text-2xl">
🎓
</div>

<h1 class="text-2xl font-bold mt-4">
Diponegoro University
</h1>

<p class="text-gray-500 text-sm">
Computer Engineering LMS
</p>

</div>

<form method="POST" action="{{ route('register') }}">
@csrf

<!-- NAME -->

<div>
<label class="block text-sm font-medium text-gray-700">
Name
</label>

<input type="text"
name="name"
value="{{ old('name') }}"
required
class="mt-1 w-full border-gray-300 rounded-lg shadow-sm"
placeholder="Your name">

</div>


<!-- EMAIL -->

<div class="mt-4">
<label class="block text-sm font-medium text-gray-700">
Email
</label>

<input type="email"
name="email"
value="{{ old('email') }}"
required
class="mt-1 w-full border-gray-300 rounded-lg shadow-sm"
placeholder="your.email@undip.ac.id">

</div>


<!-- ROLE -->

<div class="mt-4">
<label class="block text-sm font-medium text-gray-700">
Role
</label>

<select name="role"
class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">

<option value="student">Student</option>
<option value="lecturer">Lecturer</option>
<option value="admin">Admin</option>

</select>

</div>


<!-- PASSWORD -->

<div class="mt-4">
<label class="block text-sm font-medium text-gray-700">
Password
</label>

<input type="password"
name="password"
required
class="mt-1 w-full border-gray-300 rounded-lg shadow-sm"
placeholder="********">

</div>


<!-- CONFIRM PASSWORD -->

<div class="mt-4">
<label class="block text-sm font-medium text-gray-700">
Confirm Password
</label>

<input type="password"
name="password_confirmation"
required
class="mt-1 w-full border-gray-300 rounded-lg shadow-sm"
placeholder="********">

</div>


<!-- REGISTER BUTTON -->

<button
type="submit"
class="mt-6 w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white py-2 rounded-lg">

Register

</button>


<div class="text-center mt-4 text-sm">

Already have an account?

<a href="{{ route('login') }}"
class="text-indigo-600 hover:underline">

Login here

</a>

</div>

</form>

</div>

</div>

</x-guest-layout>
