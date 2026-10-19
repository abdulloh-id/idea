<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">
                Capture your thoughts
            </p>

            <x-card
                x-data
                @click="$dispatch('open-modal', 'create-idea')"
                is="button"
                type="button"
                data-test="create-idea-button"
                class="mt-10 cursor-pointer h-32 w-full text-left"
            >
                <p>What's the idea?</p>
            </x-card>
        </header>

        <div>
            <a href="/ideas" class="btn {{ $status === null ? '' : 'btn-outlined' }}">All <span
                    class="text-xs pl-3">{{ $statusCounts->get('all') }}</span></a>
            @foreach (App\IdeaStatus::cases() as $ideaStatus)
                <a href="/ideas?status={{ $ideaStatus->value }}"
                    class="btn {{ $status === $ideaStatus->value ? '' : 'btn-outlined' }}">
                    {{ $ideaStatus->label() }} <span
                        class="text-xs pl-3">{{ $statusCounts->get($ideaStatus->value) }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-10 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6">
                @forelse ($ideas as $idea)
                    <x-card href="{{ route('idea.show', $idea) }}">

                        @if ($idea->image_path)
                            <div class="mb-4 -mx-4 -mt-4 rounded-t-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="" class="w-full h-auto object-cover">
                            </div>
                        @endif

                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>

                        <div class="mt-1">
                            <x-idea.status-label status="{{ $idea->status }}">
                                {{ $idea->status->label() }}
                            </x-idea.status-label>
                        </div>

                        <div class="mt-5 line-clamp-3">{{ $idea->description }}</div>
                        <div class="text-xs mt-4">{{ $idea->created_at->diffForHumans() }}</div>
                    </x-card>
                @empty
                    <x-card>
                        No ideas yet. Create one now.
                    </x-card>
                @endforelse
            </div>
        </div>

        <x-modal name="create-idea" title="New Idea">
            <form
                x-data="{
                    status: 'pending',
                    links: [],
                    newLink: '',
                    steps: [],
                    newStep: ''
                }"
                method="POST"
                action="{{ route('idea.store') }}"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="space-y-6">
                    <x-form.field
                        label="Title"
                        name="title"
                        placeholder="Enter a title for your idea"
                        autofocus
                        required
                    />

                    <div class="space-y-2">
                        <label for="status" class="label">Status</label>

                        <div class="flex gap-x-3">
                            @foreach (App\IdeaStatus::cases() as $status)
                            <button
                                type="button"
                                @click="status = @js($status->value)"
                                class="btn flex-1 h-10"
                                data-test="button-status-{{ $status->value }}"
                                :class="{'btn-outlined': status !== @js($status->value)}"
                            >{{ $status->label() }}</button>
                            @endforeach

                            <input type="hidden" name="status" :value="status">
                        </div>

                        <x-form.error name="status" />
                    </div>

                    <div class="space-y-2">
                        <label for="image" class="label">Featured Image</label>

                        <input type="file" name="image" id="image" accept="image/*">
                        <x-form.error name="image" />
                    </div>

                    <x-form.field
                        label="Description"
                        name="description"
                        type="textarea"
                        placeholder="Describe your idea..."
                        autofocus
                    />

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Actionable Steps</legend>

                            <template x-for="(step, index) in steps">
                                <div class="flex gap-x-2 items-center">
                                    <input name="steps[]" x-model="step" class="input">

                                    <button
                                        type="button"
                                        @click="steps.splice(index, 1)"
                                        aria-label="Remove step"
                                        class="form-muted-icon"
                                    >
                                        <x-icons.close/>
                                    </button>
                                </div>
                            </template>
                        </fieldset>

                        <div class="flex gap-x-2 items-center">
                            <input
                                x-model="newStep"
                                id="new-step"
                                data-test="new-step"
                                placeholder="What needs to be done?"
                                spellcheck="false"
                                class="input flex-1"
                            >
                            <button
                                type="button"
                                @click="steps.push(newStep.trim()); newStep = ''"
                                :disabled="newStep.trim().length === 0"
                                aria-label="Add a new step"
                                data-test="submit-new-step-button"
                                class="form-muted-icon"
                            >
                                <x-icons.close class="rotate-45" />
                            </button>
                        </div>
                    </div>

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Links</legend>

                            <template x-for="(link, index) in links">
                                <div class="flex gap-x-2 items-center">
                                    <input name="links[]" x-model="link" class="input">

                                    <button
                                        type="button"
                                        @click="links.splice(index, 1)"
                                        aria-label="Remove link"
                                        class="form-muted-icon"
                                    >
                                        <x-icons.close/>
                                    </button>
                                </div>
                            </template>
                        </fieldset>

                        <div class="flex gap-x-2 items-center">
                            <input
                                x-model="newLink"
                                type="url"
                                id="new-link"
                                data-test="new-link"
                                placeholder="https://example.com"
                                autocomplete="url"
                                spellcheck="false"
                                class="input flex-1"
                            >
                            <button
                                type="button"
                                @click="links.push(newLink.trim()); newLink = ''"
                                :disabled="newLink.trim().length === 0"
                                aria-label="Add a new link"
                                data-test="submit-new-link-button"
                                class="form-muted-icon"
                            >
                                <x-icons.close class="rotate-45" />
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-x-3 mt-2">
                    <button
                    type="button"
                    class="btn btn-outlined"
                    @click="$dispatch('close-modal')"
                    >Cancel</button>
                    <button type="submit" class="btn" data-test="submit-idea-button">Create</button>
                </div>
            </form>
        </x-modal>
    </div>
</x-layout>
