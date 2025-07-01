@extends('welcome')
@section('title', 'Contact')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <h2 class="text-2xl font-semibold mb-6">Nous contacter</h2>

    <form method="POST" action="" class="space-y-4">
        @csrf
        <input type="text" name="name" class="w-full p-2 border rounded" placeholder="Votre nom" required>
        <input type="email" name="email" class="w-full p-2 border rounded" placeholder="Votre email" required>
        <textarea name="message" class="w-full p-2 border rounded" placeholder="Votre message" rows="5" required></textarea>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Envoyer</button>
    </form>
</div>
@endsection
