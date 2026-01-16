<x-layouts.public :title="$page->title" :meta_description="$page->meta_description">
    <article>
        <h1 class="text-4xl font-bold mb-6">{{ $page->title }}</h1>
        @foreach($page->template->fields as $field)
            @php($value = $page->field($field->name))
            @if($value)
                <div class="mb-6">
                    @switch($field->type)
                        @case('image')
                            <img src="{{ Storage::url($value) }}" alt="{{ $field->label }}" class="rounded-lg max-w-full">
                        @break
                        @case('richtext')
                            <div class="prose max-w-none">{!! $value !!}</div>
                        @break
                        @case('boolean')
                            {{-- Developer decides how to display booleans --}}
                        @break
                        @default
                        <p>{{ $value }}</p>
                    @endswitch
                </div>
            @endif
        @endforeach
    </article>
</x-layouts.public>
