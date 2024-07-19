<x-app-layout>
    @section('content')
    <div class="w-full pt-4">
        <h2 class="font-semibold text-center text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </div>
    @if (Auth::user()-> role != 'user')
    <div class="mt-5 px-3">
        <h2>Last 3 Users Registered</h2>
        <div class="my-4">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Name</th>
                        <th class="px-4 py-2 border">Email</th>
                        <th class="px-4 py-2 border">Phone</th>
                        <th class="px-4 py-2 border">Address</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-2 border">{{ $user->name }}</td>
                        <td class="px-4 py-2 border">{{ $user->email }}</td>
                        <td class="px-4 py-2 border">{{ $user->phone }}</td>
                        <td class="px-4 py-2 border">
                            {{ $user->street }}, {{ $user->city }}, {{ $user->pincode }}
                        </td>
                        <td class="px-4 py-2 border">
                            <a href="https://www.google.com/maps?q={{ $user->latitude }},{{ $user->longitude }}" target="_blank" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700">Track Location</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 border text-center">No users</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (Route::has('configuration.users.index'))
        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('configuration.users.index') }}">
            {{ __('See Whole List') }}
        </a>
        @endif
    </div>
    @endif
    <div class="mt-5 px-3">
        <h2>Last 3 Pending Order</h2>
        <div class="my-4">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Order Date</th>
                        <th class="px-4 py-2 border">User Name</th>
                        <th class="px-4 py-2 border">Wastage</th>
                        <th class="px-4 py-2 border">Waste Image</th>
                        <th class="px-4 py-2 border">Quantity</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pending_orders as $order)
                    <tr>
                        <td class="px-4 py-2 border">{{ $order->order_date }}</td>
                        <td class="px-4 py-2 border">{{ $order->user->name }}</td>
                        <td class="px-4 py-2 border">{{ $order->category->name }}</td>
                        <td class="px-4 py-2 border w-3/12">
                            <a href="{{ asset($order->user_waste_image) }}" target="_blank">
                                <img class="w-5/12" src="{{ asset($order->user_waste_image) }}" alt="{{ $order->id }}">
                            </a>
                        </td>
                        <td class="px-4 py-2 border">
                            {{ $order->quantity }} KG
                        </td>
                        <td class="px-4 py-2 border">
                            <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700">Track Location</a>
                            @if ($order->amount != null)
                            @if (Route::has('configuration.orderlist.edit') && Auth::user()->role == 'wastage_collector')
                            <a class="bg-green-500 text-white px-3 py-1 rounded hover:bg-blue-700" href="{{ route('configuration.orderlist.edit', $order->id) }}">
                                {{ __('Complete') }}
                            </a>
                            @endif
                            @else
                            @if (Auth::user()-> role != 'user')
                            <a class="bg-gray-500 text-white px-3 py-1 rounded">Payment Pending</a>
                            @else
                            <a class="bg-green-500 text-white px-3 py-1 rounded hover:bg-blue-700" href="{{ route('pay.create', $order->id) }}">
                                {{ __('Make Payment') }}
                            </a>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 border text-center">No orders</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (Route::has('configuration.orderlist.index'))
        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('configuration.orderlist.index') }}">
            {{ __('See Whole List') }}
        </a>
        @endif
    </div>
    <div class="mt-5 px-3">
        <h2>Last 3 Completed Order</h2>
        <div class="my-4">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Order Date</th>
                        <th class="px-4 py-2 border">Collect Date</th>
                        <th class="px-4 py-2 border">User Name</th>
                        <th class="px-4 py-2 border">Wastage</th>
                        <th class="px-4 py-2 border">User Image</th>
                        <th class="px-4 py-2 border">Collector Image</th>
                        <th class="px-4 py-2 border">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($completed_orders as $order)
                    <tr>
                        <td class="px-4 py-2 border">{{ $order->order_date }}</td>
                        <td class="px-4 py-2 border">{{ $order->collect_date }}</td>
                        <td class="px-4 py-2 border">{{ $order->user->name }}</td>
                        <td class="px-4 py-2 border">{{ $order->category->name }}</td>
                        <td class="px-4 py-2 border w-3/12">
                            <a href="{{ asset($order->user_waste_image) }}" target="_blank">
                                <img class="w-5/12" src="{{ asset($order->user_waste_image) }}" alt="{{ $order->id }}">
                            </a>
                        </td>
                        <td class="px-4 py-2 border w-3/12">
                            <a href="{{ asset($order->waste_image) }}" target="_blank">
                                <img class="w-5/12" src="{{ asset($order->waste_image) }}" alt="{{ $order->id }}">
                            </a>
                        </td>
                        <td class="px-4 py-2 border">{{ $order->amount }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 border text-center text-red-900">No orders</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (Route::has('configuration.orderlist.create') && Auth::user()->role == 'user')
        <a class="mr-5 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('configuration.orderlist.create') }}">
            {{ __('Create Order') }}
        </a>
        @endif
        @if (Route::has('configuration.orderlist.index'))
        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('configuration.orderlist.index') }}">
            {{ __('See Whole List') }}
        </a>
        @endif
    </div>
    @stop
</x-app-layout>