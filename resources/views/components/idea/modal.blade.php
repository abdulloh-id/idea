@props(['idea' => new App\Models\Idea()])

<x-modal
    name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}"
    title="{{ $idea->exists ? 'Edit Idea' : 'New Idea' }}"
>
    <form
        x-data="{
            status: @js(old('status', $idea->status->value)),
            links: @js(old('links', $idea->links)),
            newLink: '',
            steps: @js(old('steps', $idea->steps->map(fn ($step) => $step->description))),
            newStep: ''
        }"
        method="POST"
        action="{{ $idea->exists ? route('idea.update', $idea) : route('idea.store') }}"
        enctype="multipart/form-data"
    >
        @csrf

        @if ($idea->exists)
            @method('PATCH')
        @endif

        <div class="space-y-6">
            <x-form.field
                label="Title"
                name="title"
                placeholder="Enter a title for your idea"
                autofocus
                required
                :value="$idea->title"
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

                @if ($idea->image_path)
                    <div class="space-y-2">
                        <img src="{{ asset('storage/' . $idea->image_path) }}"
                            alt="{{ $idea->title }}"
                            class="w-full h-auto object-cover rounded-lg">
                    </div>

                    <button type="submit" class="btn btn-outlined h-10 w-full" form="delete-image-form">Remove Image</button>
                @endif

                <input type="file" name="image" id="image" accept="image/*">
                <x-form.error name="image" />
            </div>

            <x-form.field
                label="Description"
                name="description"
                type="textarea"
                placeholder="Describe your idea..."
                autofocus
                :value="$idea->description"
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
            <button type="submit" class="btn" data-test="submit-idea-button">{{ $idea->exists ? 'Update' : 'Create' }}</button>
        </div>
    </form>

    @if ($idea->image_path)
        <form
            id="delete-image-form"
            action="{{ route('idea.image.destroy', $idea) }}"
            method="POST"
        >
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-modal>
