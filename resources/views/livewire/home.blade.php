@php
    $posts = App\Models\Post::where('is_group_post', 0)->latest()->get();
@endphp
<div class="container grid px-6 mx-auto">
    @if ($posts->count() > 0)
        <div class="flex flex-col items-center gap-6 my-8">
            <!-- Card -->
            @foreach ($posts as $post)
                @include('livewire.components.card-post')
            @endforeach
        </div>
    @else
        <div class="flex flex-row items-center justify-center h-160">
            <img src="{{ asset('images/website/zoom.gif') }}" alt="" width="150px">
            <div class="mt-6 text-center">
                <h1 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">No Posts Found</h1>
                <p class="mt-2 text-gray-500 dark:text-gray-300">There is no posts. Please check back later.</p>
            </div>
        </div>
    @endif
</div>
