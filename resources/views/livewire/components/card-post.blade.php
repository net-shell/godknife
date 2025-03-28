@php
    $saved_posts = App\Models\SavedPost::where('user_id', auth()->id())->get();
    $get_saved_posts_id = [];
    foreach ($saved_posts as $saved_post) {
        $get_saved_posts_id[] = $saved_post->post_id;
    }
@endphp

<div>
    <div class="flex px-2 mt-4 mb-2">
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
                <img src="{{ $post->user->profile }}" alt="{{ $post->user->first_name }} {{ $post->user->last_name }}"
                    class="w-12 h-12 mr-4 rounded-full">
            </a>
            <div class="text-sm font-bold text-gray-700 dark:text-gray-200">
                <a href="{{ route('profile.show', $post->user->username) }}">
                    <p>{{ $post->user->full_name }}</p>
                </a>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    @if (!empty($post->user->location))
                        <span>{{ $post->user->location }}</span>
                    @endif
                    {{ $post->created_at->diffForHumans() }}
                </p>
            </div>
        @endif
    </div>

    <div class="flex flex-row justify-stretch bg-gray-100 rounded-lg shadow-xs dark:bg-gray-800">
        <div class="flex-1">
            <a href="{{ route('post.show', $post->uuid) }}">
                <img src="{{ asset('images/thumbnails/' . $post->thumbnail) }}" alt="{{ $post->title }}"
                    class="object-cover object-center h-80 w-full h-auto text-center rounded-l-lg">
            </a>
        </div>

        <div class="flex-1 flex flex-col justify-stretch">
            <div class="flex items-center justify-stretch">
                <div class="inline-flex mx-4 rounded-lg shadow-sm" role="group">
                    @if (in_array($post->id, $get_saved_posts_id))
                        <a href="{{ route('unsave-post', $post->id) }}"
                            class="px-2 py-1 text-sm font-medium text-gray-900 bg-transparent border border-gray-900 rounded-lg hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                            <svg fill="currentColor" class="w-6 h-6" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20.808,11.079C19.829,16.132,12,20.5,12,20.5s-7.829-4.368-8.808-9.421C2.227,6.1,5.066,3.5,8,3.5a4.444,4.444,0,0,1,4,2,4.444,4.444,0,0,1,4-2C18.934,3.5,21.773,6.1,20.808,11.079Z" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('save-post', $post->id) }}"
                            class="px-2 py-1 text-sm font-medium text-gray-900 bg-transparent border border-gray-900 rounded-lg hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                            <svg fill="currentColor" class="w-6 h-6" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20.5,4.609A5.811,5.811,0,0,0,16,2.5a5.75,5.75,0,0,0-4,1.455A5.75,5.75,0,0,0,8,2.5,5.811,5.811,0,0,0,3.5,4.609c-.953,1.156-1.95,3.249-1.289,6.66,1.055,5.447,8.966,9.917,9.3,10.1a1,1,0,0,0,.974,0c.336-.187,8.247-4.657,9.3-10.1C22.45,7.858,21.453,5.765,20.5,4.609Zm-.674,6.28C19.08,14.74,13.658,18.322,12,19.34c-2.336-1.41-7.142-4.95-7.821-8.451-.513-2.646.189-4.183.869-5.007A3.819,3.819,0,0,1,8,4.5a3.493,3.493,0,0,1,3.115,1.469,1.005,1.005,0,0,0,1.76.011A3.489,3.489,0,0,1,16,4.5a3.819,3.819,0,0,1,2.959,1.382C19.637,6.706,20.339,8.243,19.826,10.889Z" />
                            </svg>
                        </a>
                    @endif
                </div>

                <div class="px-4 py-2">
                    <a href="{{ route('post.show', $post->uuid) }}">
                        <h2 class="text-lg font-bold text-gray-700 dark:text-gray-100">
                            {{ $post->title }}
                        </h2>
                    </a>
                </div>
            </div>

            <div class="flex-1 px-4 py-2">
                @php
                    $content = $post->content;
                    $content = strip_tags($content);
                    $content = \Str::limit($content, 150, '...');
                @endphp
                <p class="text-sm text-gray-500">{{ $content }}</p>
            </div>

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
                            class="p-4 font-medium rounded dark:text-red-100">
                            <svg class="w-6 h-6" stroke="currentColor" viewBox="0 -0.5 21 21" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g stroke-width="1" fill="currentColor" fill-rule="evenodd">
                                    <g id="Dribbble-Light-Preview" transform="translate(-219.000000, -760.000000)">
                                        <g id="icons" transform="translate(56.000000, 160.000000)">
                                            <path
                                                d="M163,610.021159 L163,618.021159 C163,619.126159 163.93975,620.000159 165.1,620.000159 L167.199999,620.000159 L167.199999,608.000159 L165.1,608.000159 C163.93975,608.000159 163,608.916159 163,610.021159 M183.925446,611.355159 L182.100546,617.890159 C181.800246,619.131159 180.639996,620.000159 179.302297,620.000159 L169.299999,620.000159 L169.299999,608.021159 L171.104948,601.826159 C171.318098,600.509159 172.754498,599.625159 174.209798,600.157159 C175.080247,600.476159 175.599997,601.339159 175.599997,602.228159 L175.599997,607.021159 C175.599997,607.573159 176.070397,608.000159 176.649997,608.000159 L181.127196,608.000159 C182.974146,608.000159 184.340196,609.642159 183.925446,611.355159"
                                                id="like-[#1386]">

                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('post.like', $post->id, 'like') }}"
                            class="p-4 font-medium rounded hover:stroke-green-700 dark:text-green-100">
                            <svg class="w-6 h-6" stroke="currentColor" viewBox="0 -0.5 21 21" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Dribbble-Light-Preview" transform="translate(-219.000000, -760.000000)">
                                        <g id="icons" transform="translate(56.000000, 160.000000)">
                                            <path
                                                d="M163,610.021159 L163,618.021159 C163,619.126159 163.93975,620.000159 165.1,620.000159 L167.199999,620.000159 L167.199999,608.000159 L165.1,608.000159 C163.93975,608.000159 163,608.916159 163,610.021159 M183.925446,611.355159 L182.100546,617.890159 C181.800246,619.131159 180.639996,620.000159 179.302297,620.000159 L169.299999,620.000159 L169.299999,608.021159 L171.104948,601.826159 C171.318098,600.509159 172.754498,599.625159 174.209798,600.157159 C175.080247,600.476159 175.599997,601.339159 175.599997,602.228159 L175.599997,607.021159 C175.599997,607.573159 176.070397,608.000159 176.649997,608.000159 L181.127196,608.000159 C182.974146,608.000159 184.340196,609.642159 183.925446,611.355159"
                                                id="like-[#1386]">

                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    @endif
                    <span title="харесвания" class="flex-1">
                        @if ($post->likes > 0)
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-100">харесвания</span>
                        @endif
                    </span>
                </div>
                <div class="flex items-center justify-center">
                    <a href="{{ route('post.show', $post->uuid) }}" class="flex items-center">
                        <span class="p-4 font-medium text-gray-700 rounded dark:text-gray-100" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                            </svg>
                        </span>
                    </a>
                    <span title="коментара">
                        @if ($post->comments > 0)
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-100">коментара</span>
                        @endif
                    </span>
                </div>
                <div class="flex items-center justify-center ">
                    <a href="{{ route('share-post', $post->id) }}"
                        class="p-4 font-medium text-gray-700 rounded dark:text-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                        </svg>
                    </a>
                    <span title="споделяния" class="flex-1">
                        @if ($post->shares > 0)
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-100">споделяния</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
