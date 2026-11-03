@props(['name', 'title'])

<div
    x-data="{ show: false, name: @js($name) }"
    x-show="show"
    @open-modal.window="if ($event.detail === name) show = true"
    @keydown.escape.window="show = false"
    @close-modal="show = false"
    x-transition:enter="ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-4 -translate-x-4"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 -translate-y-4 -translate-x-4"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
    style="display: none"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-{{ $name }}-title"
    :aria-hidden="!show"
    tabindex="-1"
>
    <x-card @click.away="show = false" class="shadow-xl max-w-2xl w-full max-h-[85dvh] overflow-y-auto flex flex-col p-6 md:p-8">

        <!-- Modal Header -->
        <div class="flex justify-between items-center pb-4 border-b border-border/40">
            <h2 id="modal-{{ $name }}-title" class="text-xl font-bold tracking-tight">
                {{ $title }}
            </h2>

            <button @click="show = false" aria-label="Close modal" class="text-muted-foreground hover:text-foreground transition">
                <x-icons.close/>
            </button>
        </div>

        <!-- Modal Body / Content -->
        <div class="mt-6 space-y-6">
            {{ $slot }}
        </div>

    </x-card>
</div>
