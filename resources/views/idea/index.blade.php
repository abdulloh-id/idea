<x-layout>
    <!-- Header Section -->
    <header class="mb-8 md:mb-12">
        <h1 class="text-3xl font-bold tracking-tight">Ideas</h1>
        <p class="text-muted-foreground text-sm mt-1.5">
            Capture your thoughts
        </p>

        <x-card
            x-data
            @click="$dispatch('open-modal', 'create-idea')"
            is="button"
            type="button"
            data-test="create-idea-button"
            class="mt-6 cursor-pointer h-28 w-full text-left flex items-center transition hover:border-primary/50"
        >
            <p class="text-muted-foreground font-medium">What's the idea?</p>
        </x-card>
    </header>

    <!-- Filter Navigation Tabs -->
    <div class="flex flex-wrap items-center gap-2 mb-8">
        <a href="/ideas" class="btn {{ $status === null ? '' : 'btn-outlined' }}">
            All <span class="text-xs pl-2 opacity-75">{{ $statusCounts->get('all') }}</span>
        </a>
        @foreach (App\IdeaStatus::cases() as $ideaStatus)
            <a href="/ideas?status={{ $ideaStatus->value }}"
                class="btn {{ $status === $ideaStatus->value ? '' : 'btn-outlined' }}">
                {{ $ideaStatus->label() }}
                <span class="text-xs pl-2 opacity-75">{{ $statusCounts->get($ideaStatus->value) }}</span>
            </a>
        @endforeach
    </div>

    <!-- Ideas Grid -->
    <div class="text-muted-foreground">
        <div class="grid md:grid-cols-2 gap-6">
            @forelse ($ideas as $idea)
                <x-card href="{{ route('idea.show', $idea) }}" class="flex flex-col justify-between">
                    <div>
                        @if ($idea->image_path)
                            <div class="mb-4 -mx-4 -mt-4 rounded-t-lg overflow-hidden aspect-video">
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <h3 class="text-foreground text-lg font-semibold">{{ $idea->title }}</h3>

                        <div class="mt-2">
                            <x-idea.status-label status="{{ $idea->status }}">
                                {{ $idea->status->label() }}
                            </x-idea.status-label>
                        </div>

                        <div class="mt-3 line-clamp-3 text-sm leading-relaxed">{{ Str::limit(strip_tags($idea->description), 120) }}</div>
                    </div>

                    <div class="text-xs mt-6 pt-4 border-t border-border/40 text-muted-foreground/80">
                        {{ $idea->created_at->diffForHumans() }}
                    </div>
                </x-card>
            @empty
                <div class="col-span-full">
                    <x-card class="text-center py-12">
                        <p class="text-muted-foreground">No ideas yet. Create one now.</p>
                    </x-card>
                </div>
            @endforelse
        </div>
    </div>

    <x-idea.modal />
</x-layout>
