@props(['title', 'description' => '', 'backUrl' => null])

<div class="mb-8 border-b border-black pb-4">
    <div class="flex items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-black">{{ $title }}</h1>
            @if($description)
                <p class="mt-1 text-sm text-black">{{ $description }}</p>
            @endif
        </div>
    </div>
</div>