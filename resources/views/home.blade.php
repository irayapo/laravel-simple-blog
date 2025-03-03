<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-10 sm:px-6 lg:px-8">

            {{-- for guest users --}}
            @guest
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1>Welcome to the Blog</h1>
                        <a href="{{ route('login') }}" class="text-blue-500">Login</a>
                        <a href="{{ route('register') }}" class="text-blue-500">Register</a>
                    </div>
                </div>
            @endguest

            {{-- for authenticated users --}}
            @auth
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="space-y-6 p-6">
                        <h1>Your Posts</h1>
                        @foreach ($posts as $post)
                            {{-- Loop through the posts --}}
                            <div class="rounded-md border p-5 shadow">
                                <div class="flex items-center gap-2">
                                    {{-- Display status label based on post status --}}
                                    @if ($post->status == 'active')
                                        <span class="flex-none rounded bg-green-100 px-2 py-1 text-green-800">Active</span>
                                    @elseif($post->status == 'draft')
                                        <span class="flex-none rounded bg-gray-100 px-2 py-1 text-gray-800">Draft</span>
                                    @elseif($post->status == 'scheduled')
                                        <span
                                            class="flex-none rounded bg-yellow-100 px-2 py-1 text-yellow-800">Scheduled</span>
                                    @endif
                                    <h3><a href="{{ route('posts.show', $post) }}"
                                            class="text-blue-500">{{ Str::limit($post->title, 60) }}</a></h3>
                                </div>
                                <div class="mt-4 flex items-end justify-between">
                                    <div>
                                        <div>Published:
                                            {{ $post->published_at ? $post->published_at->format('Y-m-d') : '-' }}</div>
                                        <div>Updated: {{ $post->updated_at->format('Y-m-d') }}</div>
                                    </div>
                                    <div>
                                        {{-- Show edit and delete only for the post author --}}
                                        @if ($post->user_id === auth()->id())
                                            <a href="{{ route('posts.edit', $post) }}" class="text-blue-500">Edit</a> /
                                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this post?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-500">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Pagination --}}
                        <div>{{ $posts->links() }}</div>
                    </div>
                </div>
            @endauth

        </div>
    </div>
</x-app-layout>
