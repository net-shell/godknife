@php
    $saved_posts = App\Models\SavedPost::where('user_id', auth()->id())->get();
    $get_saved_posts_id = [];
    foreach ($saved_posts as $saved_post) {
        $get_saved_posts_id[] = $saved_post->post_id;
    }
@endphp

<div class="flex flex-col p-4 bg-gray-100 rounded-lg shadow-xs dark:bg-gray-800">
    <div class="flex items-center justify-between">
        <div class="flex">
            @if ($post->is_page_post == 1)
                <img src="{{ 'images/pages/thumbnails/' . $post->page->thumbnail }}" alt="{{ $post->page->name }}"
                    class="w-12 h-12 mr-4 rounded-full">
                <div>
                    <a href="{{ route('channel.show', $post->page->uuid) }}"
                        class="text-sm font-bold text-gray-700 dark:text-gray-200">
                        {{ $post->page->name }}
                    </a>
                    <p class="text-xs text-gray-600 dark:text-gray-400"> {{ $post->page->members }}
                        абонати
                    </p>
                </div>
            @else
                <a href="{{ route('profile.show', $post->user->username) }}">
                    <img src="{{ $post->user->profile }}"
                        alt="{{ $post->user->first_name }} {{ $post->user->last_name }}"
                        class="w-12 h-12 mr-4 rounded-full">
                </a>
                <div class="text-sm font-bold text-gray-700 dark:text-gray-200">
                    <a href="{{ route('profile.show', $post->user->username) }}">
                        <p>{{ $post->user->full_name }}</p>
                    </a>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        {{ $post->created_at->diffForHumans() }}
                    </p>
                </div>
            @endif
        </div>

        <div class="inline-flex rounded-lg shadow-sm" role="group">
            @if (in_array($post->id, $get_saved_posts_id))
                <a href="{{ route('unsave-post', $post->id) }}"
                    class="px-2 py-1 text-sm font-medium text-gray-900 bg-transparent border border-gray-900 rounded-r-lg hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m3 3 1.664 1.664M21 21l-1.5-1.5m-5.485-1.242L12 17.25 4.5 21V8.742m.164-4.078a2.15 2.15 0 0 1 1.743-1.342 48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185V19.5M4.664 4.664 19.5 19.5" />
                    </svg>
                </a>
            @else
                <a href="{{ route('save-post', $post->id) }}"
                    class="px-2 py-1 text-sm font-medium text-gray-900 bg-transparent border border-gray-900 rounded-r-lg hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                    </svg>
                </a>
            @endif
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('post.show', $post->uuid) }}">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-100">
                {{ $post->title }}
            </h2>
        </a>
    </div>
    <div class="mt-4">
        <a href="{{ route('post.show', $post->uuid) }}">
            <img src="{{ asset('images/thumbnails/' . $post->thumbnail) }}" alt="{{ $post->title }}"
                class="text-center rounded-lg w-full h-auto">
        </a>
    </div>

    <div class="flex justify-between h-6">
        <span>
            @if ($post->likes > 0)
                <span class="text-xs font-bold text-gray-700 dark:text-gray-100">{{ $post->likes }}</span>
                <span class="text-xs text-gray-600 dark:text-gray-400">гласа</span>
            @endif
        </span>

        <div class="gap-6">
            <span>
                @if ($post->comments > 0)
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-100">{{ $post->comments }}</span>
                    <span class="text-xs text-gray-600 dark:text-gray-400">коментара</span>
                    <span class="text-xs font-bold text-gray-600 dark:text-white">:</span>
                @endif
            </span>
            <span>
                @if ($post->shares > 0)
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-100">{{ $post->shares }}</span>
                    <span class="text-xs text-gray-600 dark:text-gray-400">споделяния</span>
                @endif
            </span>
        </div>
    </div>
    <hr class="mt-2" />

    <div class="flex justify-between px-4">
        <div class="flex items-center justify-center ">
            @php
                $like = App\Models\Like::where([
                    'post_id' => $post->id,
                    'user_id' => auth()->id(),
                ])->exists();
            @endphp
            @if ($like)
                <a href="{{ route('post.dislike', $post->id, 'dislike') }}"
                    class="p-4 font-medium text-green-700 rounded  dark:text-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="#15803d" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m9 12.75 3 3m0 0 3-3m-3 3v-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </a>
            @else
                <a href="{{ route('post.like', $post->id, 'like') }}"
                    class="p-4 font-medium hover:stroke-green-700 rounded  dark:text-green-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </a>
            @endif

        </div>
        <div class="flex items-center justify-center">
            <a href="{{ route('post.show', $post->uuid) }}" class="flex items-center">
                <span class="p-4 font-medium text-gray-700 rounded  dark:text-gray-100" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                </span>
            </a>
        </div>
        <div class="flex items-center justify-center ">
            <a href="{{ route('share-post', $post->id) }}"
                class="p-4 font-medium text-gray-700 rounded  dark:text-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                </svg>
            </a>
        </div>

    </div>
</div>
