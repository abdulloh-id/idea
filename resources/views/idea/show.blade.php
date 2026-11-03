<x-layout>
    <div class="py-8 max-w-4xl mx-auto">

        <!-- Top Actions / Back Navigation -->
        <div class="flex justify-between items-center">
            <a href="{{ route('idea.index') }}" class="flex items-center gap-x-2 text-sm font-medium text-muted-foreground hover:text-foreground transition">
                <x-icons.arrow-back />
                Back to Ideas
            </a>

            <div class="gap-x-3 flex items-center">
                <button
                    x-data
                    class="btn btn-outlined"
                    data-test="edit-idea-button"
                    @click="$dispatch('open-modal', 'edit-idea')"
                >
                    <x-icons.external />
                    Edit Idea
                </button>
                <form method="POST" action="{{ route('idea.destroy', $idea) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outlined text-red-500">Delete</button>
                </form>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="mt-8 space-y-8">

            <!-- Featured Image -->
            @if ($idea->image_path)
                <div class="rounded-lg overflow-hidden shadow-sm">
                    <img src="{{ asset('storage/' . $idea->image_path) }}" alt="" class="w-full h-auto object-cover max-h-[480px]">
                </div>
            @endif

            <!-- Title and Meta Info -->
            <div>
                <h1 class="font-bold text-3xl md:text-4xl tracking-tight">{{ $idea->title }}</h1>

                <div class="mt-3 flex gap-x-3 items-center">
                    <x-idea.status-label :status="$idea->status->value">
                        {{ $idea->status->label() }}
                    </x-idea.status-label>

                    <div class="text-muted-foreground text-sm">
                        {{ $idea->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            @if ($idea->description)
                <x-card is="div">
                    <div class="text-foreground prose prose-invert max-w-none">
                        {!! $idea->formattedDescription !!}
                    </div>
                </x-card>
            @endif

            <!-- Actionable Steps -->
            @if ($idea->steps->count())
                <div class="space-y-3">
                    <h3 class="font-bold text-xl tracking-tight">Actionable Steps</h3>

                    <div class="space-y-2">
                        @foreach ($idea->steps as $step)
                            <x-card is="div">
                                <form method="POST" action="{{ route('step.update', $step) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center gap-x-3">
                                        <button type="submit" role="checkbox" class="size-5 flex items-center justify-center rounded-lg text-primary-foreground transition {{ $step->completed ? 'bg-primary' : 'border border-primary' }}">&check;</button>
                                        <span class="{{ $step->completed ? 'line-through text-muted-foreground' : 'text-foreground' }}">{{ $step->description }}</span>
                                    </div>
                                </form>
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Related Links -->
            @if ($idea->links->count())
                <div class="space-y-3">
                    <h3 class="font-bold text-xl tracking-tight">Links</h3>

                    <div class="space-y-2">
                        @foreach ($idea->links as $link)
                            <x-card :href="$link" class="text-primary font-medium flex gap-x-3 items-center hover:underline">
                                <x-icons.external />
                                {{ $link }}
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <x-idea.modal :idea="$idea" />
    </div>
</x-layout>
