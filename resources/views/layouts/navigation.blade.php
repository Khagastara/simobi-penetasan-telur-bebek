<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0">
                    <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-800">
                        {{ config('app.name', 'SIMOBI') }}
                    </a>
                </div>
                <div class="hidden sm:-my-px sm:ml-6 sm:flex">
                    @auth
                        @if (Auth::user()->owner)
                            <a href="{{ route('owner.dashboard') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Dashboard Owner</a>
                        @elseif (Auth::user()->pengepul)
                            <a href="{{ route('pengepul.dashboard') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Dashboard Pengepul</a>
                        @endif
                    @endauth
                </div>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Register</a>
                @else
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>
