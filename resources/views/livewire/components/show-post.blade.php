@php
    $path = parse_url(url()->current())['path'];
    $uuid = substr($path, strrpos($path, '/') + 1);
    $post = App\Models\Post::where('uuid', $uuid)->first();
@endphp
<div class="flex flex-row justify-center">
    <div class="w-3/4 max-w-xl p-6 mt-2 mb-2 bg-gray-100 rounded-lg shadow-xs dark:bg-gray-800">
        <div class="mb-2">
            <img class="rounded-lg" src="{{ asset('images/thumbnails/' . $post->thumbnail) }}">
        </div>
        <h1 class="text-3xl font-bold text-black dark:text-white">
            {{ $post->title }}
        </h1>
        <div class="flex justify-between mt-4 text-gray-700 dark:text-gray-100">
            <div class="flex">
                <div>
                    <a href="{{ route('profile.show', $post->user->username) }}">
                        <img src="{{ $post->user->profile }}" alt="{{ $post->user->full_name }}"
                            class="w-12 h-12 mr-4 rounded-full">
                    </a>
                </div>
                <div>
                    <span class="text-sm font-bold"><a
                            href="{{ route('profile.show', $post->user->username) }}">{{ $post->user->full_name }}</a></span><br>
                    <span class="text-sm font-bold">{{ $post->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between gap-6">
                <a href="{{ url('chat', $post->user->id) }}"
                    class="px-4 py-2 mb-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-blue-500 rounded shadow outline-none active:bg-blue-600 hover:shadow-md focus:outline-none sm:mr-2"
                    type="button">
                    Съобщение
                </a>
            </div>
        </div>
        <div class="mt-4 dark:text-white">
            {!! $post->content !!}
        </div>

        @php
            $post_media = App\Models\PostMedia::where('post_id', $post->id)->first();
        @endphp
        @if ($post_media && $post_media->file_type == 'image')
            @php
                $medias = json_decode($post_media->file);
            @endphp
            <div class="p-4 mt-4 text-gray-700 rounded-lg dark:text-gray-100 dark:bg-gray-900">
                <div class="flex flex-row justify-between m-4">
                    @foreach ($medias as $media)
                        <img src="{{ asset('images/posts/' . $media) }}" alt="post image" class="rounded-lg"
                            width="20%">
                    @endforeach
                </div>
            </div>
        @endif

        @php
            $commentsQuery = App\Models\Comment::with('post');
            $comments = $commentsQuery->get('*');
        @endphp
        <div class="p-4 mt-4 bg-blue-100 rounded-md dark:bg-gray-700">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-100">
                Коментари
                <span class="text-sm font-bold text-gray-500 dark:text-gray-400">
                    ({{ $commentsQuery->count() ?? 0 }})
                </span>
            </h2>
            @if (auth()->check())
                <div class="mt-4">
                    <form method="POST" action="{{ route('post.comment', $post->id, 'comment') }}">
                        @csrf
                        @if (auth()->user()?->banned_to > now('Europe/Sofia'))
                            <div
                                class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg">
                                You can't comment because your account is banned.
                            </div>
                        @else
                            <label class="w-full mt-4 text-sm">
                                <div class="relative text-gray-500 focus-within:text-orange-600">
                                    <input type="text" name="comment" id="comment"
                                        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-orange-500 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                        placeholder="Вашият коментар или въпрос" />

                                    <button type="submit"
                                        class="absolute inset-y-0 right-0 w-24 px-4 text-sm flex justify-center items-center font-medium leading-5 text-white transition-colors duration-150 bg-white border border-transparent rounded-r-md active:bg-black hover:bg-gray-700 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="black" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                        </svg>
                                    </button>
                                </div>
                            </label>
                        @endif
                    </form>
                </div>

                <div class="mt-4">
                    @forelse ($comments as $comment)
                        @if ($comment->post_id == $post->id)
                            <div
                                class="flex flex-col p-4 mt-4 text-gray-700 bg-gray-100 rounded-lg dark:text-gray-100 dark:bg-gray-900">

                                <div class="flex flex-row">
                                    <div>
                                        <img src="{{ $comment->user->profile }}" alt="{{ $comment->user->full_name }}"
                                            class="w-12 h-12 mr-4 rounded-full">
                                    </div>
                                    <div class="min-w-lg">
                                        <span class="text-sm font-bold">By <a
                                                href="{{ route('profile.show', $comment->user->username) }}">{{ '@' . $comment->user->username }}</a></span><br>
                                        <span class="text-sm font-bold">at
                                            {{ $comment->created_at->diffForHumans() }}</span>

                                    </div>
                                </div>
                                <div class="p-4 mt-4 border rounded-lg dark:text-gray-100 dark:bg-gray-800">
                                    <p>{{ $comment->comment }}</p>
                                </div>
                            </div>
                        @endif

                    @empty
                        <p class="text-gray-500">
                            Все още няма коментари.
                        </p>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</div>
