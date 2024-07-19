@extends('layouts.app') <!-- Extend the master.blade.php file -->
@section('content') <!-- Start the content section -->
<section class="w-full h-full bg-white dark:bg-gray-900">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="w-9/12 px-4 py-8 mx-auto lg:py-16">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Create New Order</h2>
        <div class="p-2">
            <!-- Body content goes here -->
            @if(Session::has('message'))
            <div class="bg-green-500 text-white px-4 py-2 rounded">
                <!-- Alert content goes here -->
                {{ Session::get('message') }}
            </div>
            @endif
        </div>
        <form method="post" action="{{ route('configuration.orderlist.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                @php($user = Auth::user())
                @if($user->role == 'user')
                <div class="sm:col-span-2">
                    <label for="order_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Order Date</label>
                    <input type="date" name="order_date" id="orderDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    @error('order_date')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Choose Wastage Type</label>
                    <select id="category_id" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="calculateAmount()">
                        <option>Choose a Wastage</option>
                        @foreach($wastages as $item)
                        <option value="{{ $item->id }}" data-cost="{{ $item->cost }}">{{ $item->name }} ({{ $item->cost }}/kg)</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity (KG)</label>
                    <input type="number" name="quantity" id="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" oninput="calculateAmount()">
                    @error('quantity')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Amount</label>
                    <input type="number" id="amount" name="amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" readonly>
                </div>
                <div class="sm:col-span-2">
                    <label for="user" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Waste Image</label>
                    <input type="file" id="userWasteImage" name="user_waste_image" />
                    @error('user_waste_image')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                @endif
            </div>

            <div class="flex items-center align-center">
                <button type="submit" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Submit
                </button>
                <a href="{{ route('configuration.orderlist.index') }}" class="button-style">
                <button type="button" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                    Cancel
                </button></a>
            </div>
        </form>
    </div>
</section>
@endsection <!-- End the content section -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('orderDate').valueAsDate = new Date();
    });

    function calculateAmount() {
        const selectedWastage = document.querySelector("#category_id option:checked");
        const wastageCost = selectedWastage ? selectedWastage.getAttribute('data-cost') : 0;
        const quantity = document.getElementById("quantity").value;
        const amount = document.getElementById("amount");
        amount.value = wastageCost * quantity;
    }
</script>
