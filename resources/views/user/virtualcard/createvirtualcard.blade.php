@extends('layouts.user')

@section('content')
<div class="mt-[130px] max-w-md mx-auto ">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border text-center">
        <i class="ti ti-credit-card text-6xl text-primary mb-4"></i>
        <h2 class="text-xl font-bold mb-2">Create Your Visa Card</h2>
        <p class="text-sm text-gray-500 mb-6">Secure virtual card for online payment </p>

        <form method="POST" action="{{ route('user.cards.store') }}">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2 text-left">Transaction PIN</label>
                <input type="password" name="pin" maxlength="4" 
                       class="w-full p-3 border rounded-lg text-center text-xl" required>
            </div>

            <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-bold hover:bg-primary/90">
                Create Card
            </button>
        </form>
    </div>
</div>
@endsection