@props(['name', 'label' => null, 'required' => false, 'value' => null])

<div wire:ignore>
    @if ($label)
        <flux:label>
            {{ $label }} @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </flux:label>
    @endif

    <input type="hidden" {{ $attributes->whereStartsWith('wire:model') }} x-ref="input-{{ $name }}">

    <div x-data x-init="const quill = new Quill($refs['editor-{{ $name }}'], {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });
    quill.root.innerHTML = $refs['input-{{ $name }}'].value || '';
    quill.on('text-change', () => {
        $refs['input-{{ $name }}'].value = quill.root.innerHTML;
        $refs['input-{{ $name }}'].dispatchEvent(new Event('input'));
    });">
        <div x-ref="editor-{{ $name }}" class="bg-white dark:bg-zinc-900"></div>
    </div>
</div>
