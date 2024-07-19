<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Page</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="container mx-auto max-w-lg p-8 bg-white rounded shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Payment Information</h2>
    @if ($order->amount == null)
    <form action="{{ route('pay.store', $order->id) }}" method="POST">
      @csrf
      <!-- Bank Information -->
      <div class="mb-4">
        <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
        <input type="text" id="bank_name" name="bank_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Bank Name">
      </div>
      <div class="mb-4">
        <label for="account_num" class="block text-sm font-medium text-gray-700">Account Number</label>
        <input type="text" id="account_num" name="account_num" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="123456789">
      </div>
      <div class="mb-4">
        <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>
        <input type="text" id="cvv" name="cvv" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="123">
      </div>

      <!-- Wastage Information -->
      <div class="mb-4">
        <h3 class="text-xl font-semibold mb-2">Wastage Information</h3>
        <p class="text-sm text-gray-700 mb-1"><strong>Order Date:</strong> {{ $order->order_date }}</p>
        <p class="text-sm text-gray-700 mb-1"><strong>Category:</strong> {{ $order->category->name }}</p>
        <p class="text-sm text-gray-700 mb-1"><strong>Total Cost: RM</strong> ${{ $order->quantity * $order->category->cost }}</p>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Submit Payment</button>
    </form>
    @else
    <p class="text-red-500">Payment already done for this order</p>
    @endif
  </div>
</body>

</html>
