{{-- In work, do what you enjoy. --}}
@php
    $path = parse_url(url()->current())['path'];
    $uuid = substr($path, strrpos($path, '/') + 1);
    $post = App\Models\Post::where('uuid', $uuid)->first();
    $squads = App\Models\Group::where('id', $post->squad_id)->first();
    $user = App\Models\User::where('id', $post->user_id)->first();
@endphp
<div class="flex flex-col items-center">
    <script>
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.remove('flex');
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
    <div id="deleteModal"
        class="absolute z-10 flex-col justify-around hidden w-1/4 px-4 py-4 text-gray-700 bg-red-100 border-t-8 border-red-600 rounded-b-lg shadow-md center-absolute dark:bg-white dark:text-gray-700">
        <div class="flex flex-col items-center justify-center">
            <img src="{{ asset('images/website/trash_bin.gif') }}" alt="" width="100px">
            <h2 class="mt-2 text-lg font-bold text-center">Are you sure to delete this post ?</h2>
            <div class="flex justify-between gap-6 mt-2">
                <a href="{{ route('post.delete', $post->uuid, 'delete') }}"
                    class="px-4 py-2 mb-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-red-600 rounded shadow outline-none active:bg-red-600 hover:shadow-md focus:outline-none sm:mr-2"
                    type="button">
                    Delete
                </a>
                <button
                    class="px-4 py-2 mb-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-gray-600 rounded shadow outline-none active:bg-gray-600 hover:shadow-md focus:outline-none sm:mr-2"
                    type="button" onclick="closeModal()">
                    Cancle
                </button>
            </div>
        </div>
    </div>

    <div class="w-3/4 max-w-md p-6 mt-2 mb-2 bg-gray-100 rounded-lg shadow-xs dark:bg-gray-800">
        <div style="background-image: url({{ asset('images/thumbnails/' . $post->thumbnail) }}); background-size: cover; background-position: center; background-repeat: no-repeat;"
            class="flex items-center justify-center rounded-lg min-h-xs">
            <div class="text-center glass-morphic min-w-xl">
                <h1 class="text-3xl font-bold text-white">{{ $post->title }}
                </h1>
            </div>
        </div>
        <div class="flex justify-between mt-4 text-gray-700 dark:text-gray-100">
            <div class="flex">
                <div>
                    <img src="{{ $user->profile }}" alt="Avatar" class="w-12 h-12 mr-4 rounded-full">
                </div>
                <div>
                    <span class="text-sm font-bold">By <a
                            href="{{ route('profile.show', $user->uuid) }}">{{ $user->first_name . ' ' . $user->last_name }}</a></span><br>
                    <span class="text-sm font-bold">at {{ $post->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @if (auth()->id() == $post->user_id)
                <div class="flex items-center justify-between gap-6">
                    <a href="{{ route('post.edit', $post->uuid) }}"
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Edit
                    </a>
                    <button onclick="openDeleteModal()"
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple">
                        Delete
                    </button>
                </div>
            @endif
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

        <hr class="mt-4 border-2" />
        <div class="p-4 mt-4 bg-blue-100 rounded-md dark:bg-gray-700">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-100">Comments</h2>

            <div class="mt-4">
                <form method="POST" action="{{ route('post.comment', $post->id, 'comment') }}">
                    @csrf

                    <label class="w-full mt-4 text-sm">
                        <div class="relative text-gray-500 focus-within:text-purple-600">
                            <input type="text" name="comment" id="comment"
                                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                placeholder="Write your comment" />

                            <button type="submit"
                                class="absolute inset-y-0 right-0 w-24 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">Comment</button>
                        </div>
                    </label>
                </form>
            </div>

            <div class="mt-4">
                @php
                    $comments = App\Models\Comment::with('post')->get('*');
                @endphp
                @forelse ($comments as $comment)
                    @if ($comment->post_id == $post->id)
                        <div
                            class="flex flex-col p-4 mt-4 text-gray-700 bg-gray-100 rounded-lg dark:text-gray-100 dark:bg-gray-900">

                            <div class="flex flex-row">
                                <div>
                                    <img src="{{ $comment->user->profile }}" alt="Avatar"
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
                    <p>No comments yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
