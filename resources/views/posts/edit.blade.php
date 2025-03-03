<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <form method="post" action="{{ route('posts.update', $post->id) }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                    value="{{ old('title', $post->title) }}" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="content" :value="__('Content')" />
                                <textarea id="content" name="content"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="6">{{ old('content', $post->content) }}</textarea>
                                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="published_at" :value="__('Publish Date')" />
                                <x-text-input id="published_at" name="published_at" type="date"
                                    class="mt-1 block w-full"
                                    value="{{ old('published_at', optional($post->published_at)->format('Y-m-d')) }}" />
                                <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                            </div>


                            <!-- Status Dropdown -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="draft"
                                        {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published"
                                        {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published
                                    </option>
                                    <option value="scheduled"
                                        {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>Scheduled
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Checkbox Save as Draft -->
                            <div>
                                <label for="is_draft" class="inline-flex items-center">
                                    <input id="is_draft" type="checkbox" value="1"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        name="is_draft"
                                        {{ old('is_draft', $post->status == 'draft' ? 'checked' : '') }}>
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Save as Draft') }}</span>
                                </label>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Update') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle draft status -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let isDraftCheckbox = document.getElementById("is_draft");
            let statusSelect = document.getElementById("status");

            function updateStatus() {
                if (isDraftCheckbox.checked) {
                    statusSelect.value = "draft";
                }
            }

            isDraftCheckbox.addEventListener("change", updateStatus);
            updateStatus();
        });
    </script>

</x-app-layout>
