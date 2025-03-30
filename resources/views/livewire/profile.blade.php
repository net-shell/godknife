{{-- Care about people's approval and you will be their prisoner. --}}
@php
    $path = parse_url(url()->current())['path'];
    $username = substr($path, strrpos($path, '/') + 1);
    $user = App\Models\User::where('username', $username)->first();

    $posts = App\Models\Post::where('user_id', $user->id)
        ->where('is_page_post', 0)
        ->where('is_group_post', 0)
        ->get()
        ->sortByDesc('created_at');

    $numOfFriends = 0;
    $numOfFriends += App\Models\Friend::where('user_id', $user->id)->where('status', 'accepted')->count();
    $numOfFriends += App\Models\Friend::where('friend_id', $user->id)->where('status', 'accepted')->count();
    $user->numOfFriends = $numOfFriends;

    $friends = App\Models\Friend::where('user_id', $user->id)->where('status', 'accepted')->get();
    $get_friends = App\Models\Friend::where('friend_id', $user->id)->where('status', 'accepted')->get();

    $numOfComments = App\Models\Comment::where('user_id', $user->id)->count();
@endphp

<main class="h-full overflow-y-auto profile-page">
    <section class="relative block h-1/2">
        @if ($user->thumbnail)
            <div class="absolute top-0 w-full h-full bg-center bg-cover"
                style="background-image: url('{{ asset('images/profiles/thumbnails/' . $user->thumbnail) }}'); background-size:cover; background-repead:no-repead;">
            @else
                <div class="absolute top-0 w-full h-full bg-center bg-cover"
                    style="background-image: url('https://picsum.photos/id/237/200/300'); background-size:cover; background-repead:no-repead;">
        @endif
        <span id="blackOverlay" class="absolute w-full h-full bg-black opacity-50"></span>
        </div>
        <div class="absolute bottom-0 left-0 right-0 top-auto w-full h-20 overflow-hidden pointer-events-none"
            style="transform: translateZ(0px)">
            <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
                version="1.1" viewBox="0 0 2560 100" x="0" y="0">
                <polygon class="text-gray-200 fill-current" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </section>
    <section class="relative pt-16">
        <div class="container px-4 mx-auto">
            <div
                class="relative flex flex-col w-full min-w-0 mb-6 -mt-48 break-words bg-teal-100 rounded-lg shadow-xl dark:bg-gray-800 dark:text-gray-200">
                <div class="px-6">
                    <div class="flex flex-wrap justify-center">
                        <div class="flex justify-center w-full px-4 lg:w-3/12 lg:order-2">
                            <div class="relative">
                                <img src="{{ $user->profile }}"
                                    class="absolute -m-16 -ml-20 align-middle border-none rounded-full shadow-xl lg:-ml-16 max-w-micro max-h-micro"
                                    alt="" width="150px" height="150px">
                            </div>
                        </div>
                        <div class="w-full px-4 lg:w-4/12 lg:order-3 lg:text-right lg:self-center">
                            <div class="px-3 py-6 sm:mt-0">
                                @if ($user->username == auth()->user()->username)
                                    <a href="{{ route('profile-edit', $user->username, 'edit') }}"
                                        class="px-4 py-2 mb-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-blue-500 rounded shadow outline-none active:bg-blue-600 hover:shadow-md focus:outline-none sm:mr-2"
                                        type="button">
                                        Редактиране на профила
                                    </a>
                                @else
                                    <a href="{{ url('chat', $user->id) }}"
                                        class="px-4 py-2 mb-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-blue-500 rounded shadow outline-none active:bg-blue-600 hover:shadow-md focus:outline-none sm:mr-2"
                                        type="button">
                                        Съобщение
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="w-full px-4 lg:w-4/12 lg:order-1">
                            <div class="flex flex-col justify-center py-4 pt-8 mt-2 text-center lg:pt-4">
                                <h3 class="mb-2 text-3xl font-semibold leading-normal text-gray-700 dark:text-gray-200">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </h3>
                                <div>
                                    @if ($user->numOfFriends > 0)
                                        <span class="text-sm"> {{ $user->numOfFriends }}
                                            @if ($user->numOfFriends > 1)
                                                приятели
                                            @else
                                                приятел
                                            @endif
                                        </span> <span class="font-bold">|</span>
                                    @endif
                                    @if ($posts->count() > 0)
                                        <span class="text-sm"> {{ $posts->count() }}
                                            @if ($posts->count() > 1)
                                                обяви
                                            @else
                                                обява
                                            @endif
                                        </span> <span class="font-bold">|</span>
                                    @endif
                                    @if ($numOfComments > 0)
                                        <span class="text-sm"> {{ $numOfComments }}
                                            @if ($numOfComments > 1)
                                                коментари
                                            @else
                                                коментар
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="py-6 mt-4 text-center border-t border-black dark:border-white">
                        <div class="flex flex-wrap justify-center">
                            <div class="w-full px-4 lg:w-9/12">
                                @if ($user->description)
                                    <p class="text-lg font-bold leading-relaxed text-gray-700 dark:text-gray-200">
                                        {{ $user->description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="flex flex-row">
        <section class="lg:w-1/2">
            @if (!$user->isAdmin)
                <div class="flex flex-wrap flex-col">
                    <div class="w-full p-4 md:flex-cols">
                        <div
                            class="w-full mx-auto overflow-hidden bg-gray-100 rounded-lg shadow-md dark:bg-gray-800 dark:text-gray-200">
                            <div>
                                @if ($user->school)
                                    <div class="flex mb-2">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                            </svg>
                                        </span>
                                        <p class="pl-2">{{ $user->school }}</p>
                                    </div>
                                @endif
                                @if ($user->college)
                                    <div class="flex mb-2">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                            </svg>
                                        </span>
                                        <p class="pl-2">{{ $user->college }}</p>
                                    </div>
                                @endif
                                @if ($user->university)
                                    <div class="flex mb-2">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                            </svg>

                                        </span>
                                        <p class="pl-2">{{ $user->university }}</p>
                                    </div>
                                @endif
                                @if ($user->work)
                                    <div class="flex mb-2">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                                            </svg>
                                        </span>
                                        <p class="pl-2">{{ $user->work }}</p>
                                    </div>
                                @endif
                                @if ($user->website)
                                    <div class="flex mb-2">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                            </svg>
                                        </span>
                                        <a href="{{ $user->website }}" class="pl-2">{{ $user->website }}</a>
                                    </div>
                                @endif
                                @if ($user->relationship)
                                    <div class="flex mb-2">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                            </svg>
                                        </span>
                                        <p class="pl-2 capitalize">{{ $user->relationship }}
                                            @if ($user->relationship != 'single')
                                                with
                                                <a href="{{ route('profile.show', $user->partner) }}"
                                                    class="font-bold text-gray-600 lowercase dark:text-white">
                                                    {{ $user->partner }}</a>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                                @if ($user->address)
                                    <div class="flex mb-2">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                                            </svg>

                                        </span>
                                        <p class="pl-2">{{ $user->address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="w-full p-4">
                        <div
                            class="w-full mx-auto overflow-hidden bg-gray-100 rounded-lg shadow-md h-106 dark:bg-gray-800 dark:text-gray-200">
                            <div class="p-6 text-2xl font-semibold">Приятели ({{ $user->numOfFriends }})</div>
                            <div class="grid grid-cols-3 gap-6 p-6 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3">
                                @foreach ($friends as $friend)
                                    @php
                                        $friend = App\Models\User::find($friend->friend_id);
                                    @endphp
                                    <div
                                        class="overflow-hidden border border-black rounded-lg max-h-sm dark:border-white">
                                        <img class="object-cover h-24 min-w-full" src="{{ $friend->profile }}"
                                            alt="">
                                        <div class="py-2 text-center">
                                            <a href="{{ route('profile.show', $friend->username) }}"
                                                class="text-xs font-semibold">{{ $friend->username }}</a>
                                        </div>
                                    </div>
                                @endforeach
                                @foreach ($get_friends as $friend)
                                    @php
                                        $friend = App\Models\User::find($friend->user_id);
                                    @endphp
                                    <div
                                        class="overflow-hidden border border-black rounded-lg max-h-sm dark:border-white">
                                        <img class="object-cover h-24 min-w-full" src="{{ $friend->profile }}"
                                            alt="">
                                        <div class="py-2 text-center">
                                            <a href="{{ route('profile.show', $friend->username) }}"
                                                class="text-xs font-semibold">{{ $friend->username }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </section>
        <section class="flex lg:w-1/2 flex-cols">
            @if ($posts->count() > 0)
                <div class="flex flex-col justify-center gap-4">
                    <!-- Card -->
                    @foreach ($posts as $post)
                        @php
                            $title = $post->title;
                        @endphp
                        @include('livewire.components.card-post')
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center h-32">
                    <div class="text-center">
                        <h1 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Няма обяви</h1>
                        <p class="mt-2 text-gray-500 dark:text-gray-300">Този потребител все още не е добавил обяви.
                        </p>
                    </div>
                </div>
            @endif
        </section>
    </div>
</main>
