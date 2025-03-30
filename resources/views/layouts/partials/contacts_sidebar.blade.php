@php
    $url = $_SERVER['REQUEST_URI'];
    $parsedUrl = parse_url($url);
    $path = $parsedUrl['path'];
    $pathSegments = explode('/', $path);
    $postType = $pathSegments[1];

    $notifications = App\Models\Notification::where('user_id', auth()->id())
        ->where('read_at', null)
        ->orderBy('created_at', 'desc')
        ->get();

@endphp

<!-- Contacts sidebar -->
<aside class="flex-shrink-0 hidden h-full w-80 overflow-x-none bg-gray-100 dark:bg-gray-800 md:block">
    {{-- The best athlete wants his opponent at his best. --}}
    @php
        $user = auth()->user();

        $friends = App\Models\Friend::where('user_id', auth()->id())
            ->where('status', 'accepted')
            ->get();
        $get_friends = App\Models\Friend::where('friend_id', auth()->id())
            ->where('status', 'accepted')
            ->get();

        // $get_request = App\Models\Friend::where('friend_id', auth()->id())->get();
        $get_request = App\Models\Friend::where('friend_id', auth()->id())
            ->where('status', 'pending')
            ->get();
        $suggestions = App\Models\User::where('id', '!=', auth()->id())
            ->notInAdmins()
            ->get();
    @endphp
    @if (session()->has('friend_request'))
        <script>
            setTimeout(function() {
                document.querySelector('.alert').remove();
            }, 5000);
        </script>
        <div
            class="absolute top-0 right-0 z-10 flex flex-col justify-around w-auto px-4 py-4 text-gray-700 bg-gray-100 border-t-8 border-green-600 rounded-b-lg shadow-md alert dark:bg-white dark:text-gray-700">
            <div class="flex items-center justify-between">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="pl-2 font-bold">{{ session('friend_request') }}</span>
            </div>
        </div>
        @php
            session()->forget('friend_request');
        @endphp
    @endif

    <p class="py-4 text-gray-700 dark:text-gray-100 text-sm font-bold text-center">
        - Покани за приятелство -
    </p>

    <div id="request_section" class="bg-gray-100 shadow-md dark:bg-gray-700">
        @if ($get_request->isEmpty())
            <div class="flex items-center justify-center">
                <p class="text-sm text-center font-bold text-gray-700 dark:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 m-auto">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                    Нямате нови покани за приятелство.
                </p>
            </div>
        @else
            <div class="flex flex-col">
                @foreach ($get_request as $request)
                    @php
                        $request = App\Models\User::find($request->user_id);
                    @endphp
                    @if (!$get_request->contains('user_id', $request->id) || $get_request->contains('friend_id', $request->id))
                        @continue
                    @else
                        <div class="flex flex-col justify-between flex-1 shadow-lg">
                            <div class="px-4 py-2 flex flex-1">
                                <img src="{{ $request->profile }}" alt="{{ $request->username }}"
                                    class="w-12 h-12 mr-4 rounded-full">
                                <div>
                                    <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                        {{ $request->first_name }} {{ $request->last_name }}
                                    </h2>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ '@' . $request->username }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div class="flex justify-between mt-2">
                                    <a href="{{ route('accept-friend', $request->id) }}"
                                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-teal-600 border border-transparent active:bg-teal-600 hover:bg-teal-700 focus:outline-none focus:shadow-outline-purple">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                        </svg>
                                        Приеми
                                    </a>
                                    <a href="{{ route('reject-friend', $request->id) }}"
                                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                        </svg>
                                        Откажи
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <p class="p-4 text-gray-700 dark:text-gray-100 text-sm font-bold text-center">
        - Приятели -
    </p>

    <div id="friends_section" class="bg-gray-100 shadow-md dark:bg-gray-700">
        @if ($friends->isEmpty() && $get_friends->isEmpty())
            <div class="flex m-4 items-center justify-center">
                <p class="text-sm text-center font-bold text-gray-700 dark:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 m-auto">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    Все още нямате приятели.
                </p>
            </div>
        @else
            <div class="flex flex-col">
                @foreach ($friends as $friend)
                    @php
                        $friend = App\Models\User::find($friend->friend_id);
                    @endphp
                    <div class="flex flex-row items-stretch flex-1 shadow-lg">
                        <a class="px-4 py-2 flex flex-1 hover:bg-blue-500"
                            href="{{ route('profile.show', $friend->username) }}">
                            <img src="{{ $friend->profile }}" alt="{{ $friend->username }}"
                                class="w-12 h-12 mr-4 rounded-full">
                            <div>
                                <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ $friend->first_name }} {{ $friend->last_name }}
                                </h2>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ '@' . $friend->username }}</p>
                            </div>
                        </a>

                        <a href="{{ url('chat', $friend->id) }}"
                            class="p-5 h-full text-xs font-bold text-white uppercase transition-all duration-150 ease-linear hover:bg-blue-500 outline-none active:bg-blue-600 hover:shadow-md focus:outline-none sm:mr-2"
                            type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="black" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                        </a>
                    </div>
                @endforeach
                @foreach ($get_friends as $friend)
                    @php
                        $friend = App\Models\User::find($friend->user_id);
                    @endphp
                    <div class="flex flex-row items-stretch flex-1 shadow-lg">
                        <a class="px-4 py-2 flex flex-1 hover:bg-blue-500"
                            href="{{ route('profile.show', $friend->username) }}">
                            <img src="{{ $friend->profile }}" alt="{{ $friend->username }}"
                                class="w-12 h-12 mr-4 rounded-full">
                            <div>
                                <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ $friend->first_name }} {{ $friend->last_name }}
                                </h2>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ '@' . $friend->username }}</p>
                            </div>
                        </a>

                        <a href="{{ url('chat', $friend->id) }}"
                            class="p-5 h-full text-xs font-bold text-white uppercase transition-all duration-150 ease-linear hover:bg-blue-500 outline-none active:bg-blue-600 hover:shadow-md focus:outline-none sm:mr-2"
                            type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="black" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <p class="p-4 text-gray-700 dark:text-gray-100 text-sm font-bold text-center">
        - Предложения за приятелство -
    </p>

    <div id="suggestions_section" class="bg-gray-100 rounded-lg shadow-md dark:bg-gray-700">
        @if ($suggestions->isEmpty())
            <div class="p-4 flex items-center justify-center">
                <p class="text-sm text-center font-bold text-gray-700 dark:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 m-auto">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span>Няма нови предложения за приятелство.</span>
                </p>
            </div>
        @else
            <div class="flex flex-col">
                @foreach ($suggestions as $suggestion)
                    @if (
                        $get_request->contains('user_id', $suggestion->id) ||
                            $get_request->contains('friend_id', $suggestion->id) ||
                            $friends->contains('friend_id', $suggestion->id) ||
                            $get_friends->contains('user_id', $suggestion->id))
                        @continue
                    @endif
                    <div class="flex flex-col justify-between flex-1 shadow-lg">
                        <div class="flex flex-1 p-4">
                            <img src="{{ $suggestion->profile }}" alt="{{ $suggestion->username }}"
                                class="w-12 h-12 mr-4 rounded-full">
                            <div>
                                <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ $suggestion->first_name }} {{ $suggestion->last_name }}
                                </h2>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ '@' . $suggestion->username }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            @if (App\Models\Friend::Where([
                                    'friend_id' => $suggestion->id,
                                    'user_id' => auth()->id(),
                                    'status' => 'pending',
                                ])->exists())
                                <a href="{{ route('cancle-friend', $suggestion->id) }}"
                                    class="flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                    </svg>
                                    Отмени поканата
                                </a>
                            @elseif (App\Models\Friend::Where([
                                    'friend_id' => auth()->id(),
                                    'user_id' => $user->id,
                                    'status' => 'rejected',
                                ])->exists())
                                <a href="{{ route('add-friend', $suggestion->id) }}"
                                    class="flex items-center justify-center px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-purple">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                    </svg>
                                    Добави в приятели
                                </a>
                            @else
                                <a href="{{ route('add-friend', $suggestion->id) }}"
                                    class="flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-purple">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                    </svg>
                                    <span>Добави в приятели</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>
        @endif

    </div>
    </div>
</aside>
