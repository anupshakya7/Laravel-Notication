<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                   @foreach(auth()->user()->unreadNotifications as $notification)
                        <div class="bg-blue-700 p-4 m-3">
                            {{$notification->data['name']}} start following you!!!
                            <a href="{{route('markAsRead',$notification->id)}}" class="p-2 bg-red-400 text-white rounded-lg"> Mark as Read</a>
                        </div>
                   @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
