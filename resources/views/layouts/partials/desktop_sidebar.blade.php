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

<!-- Desktop sidebar -->
<aside class="z-20 flex-shrink-0 hidden w-64 overflow-y-auto bg-gray-100 dark:bg-gray-800 md:block">
    <div class="py-4 text-gray-500 dark:text-gray-400">
        <a href="{{ url('/') }}" class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="#">
            {{ env('APP_NAME') }}
        </a>
        @include('layouts.partials.nav-items')
    </div>
</aside>
<script>
    let checkInput = document.getElementById('checkDeleteName');
    let deleteButton = document.getElementById('deleteAccount');
    let checkName = @json(auth()->user()->username);

    function checkDeleteName() {
        if (checkInput.value === checkName) {
            deleteButton.href = "{{ route('profile.delete', auth()->user()->username, 'delete') }}";
        } else {
            deleteButton.href = "";
        }
    }
</script>
