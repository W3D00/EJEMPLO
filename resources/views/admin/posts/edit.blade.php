<x-layouts.admin>
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush
    
    <div class="mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('admin.dashboard')}}">Dahsboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{route('admin.posts.index')}}">Posts</flux:breadcrumbs.item>
            <flux:breadcrumbs.item >Editar</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
    
    <form action="{{route('admin.posts.update', $post)}}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="relative mb-2">
            <img id="imgPreview" src="{{ $post->image }}" alt="" class="w-full aspect-video object-cover object-center rounded-lg">
            <div class="absolute top-8 right-8">
                <label class="bg-white px-4 py-2 rounded-lg shadow-lg cursor-pointer">
                    Cambiar imagen
                    <input class="hidden" type="file" name="image" id="image" accept="image/*" onchange="preview_image(event, '#imgPreview')">
                </label>
            </div>
        </div>
        <div class="bg-white px-6 py-8 rounded-lg shadow-lg space-y-4">
            <flux:input name="title" label="Título" value="{{old('title', $post->title)}}" />
            
            @if(!$post->published_at)
                <flux:input name="published_at" label="Fecha de publicación" type="datetime-local" value="{{old('published_at', $post->published_at->format('Y-m-d\TH:i'))}}" />
            @endif

            <flux:input name="slug" id="slug" label="Slug" value="{{old('slug', $post->slug)}}" />

            <flux:select name="category_id" label="Categoría" placeholder="Selecciona una categoría...">
                @foreach ($categories as $category)
                    <flux:select.option value="{{$category->id}}" :selected="$category->id == old('category_id',$post->category_id)">{{$category->name}}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:textarea name="excerpt" label='Resumen'>{{old('excerpt', $post->excerpt)}}</flux:textarea>

            <div>
                <p class="font-medium text-sm mb-2">Etiquetas</p>
                <select id="tags" name="tags[]" style="width: 100%" multiple="multiple">
                    @foreach ($tags as $tag)
                        <option value="{{$tag->name}}" @selected(in_array($tag->name,old('tags', $tags->pluck('name')->toArray())))>{{$tag->name}}</option>
                    @endforeach
                </select>
            </div>

            {{--<flux:textarea name="content" label='Cuerpo' rows='16'>{{old('content', $post->content)}}</flux:textarea>--}}
            <div>
                <p class="font-medium text-sm mb-2">Cuerpo</p>
                <div id="editor">
                    {!!old('content', $post->content)!!}
                </div>
                <textarea name="content" id="content" class="hidden"> {{old('content', $post->content)}}</textarea>
            </div>

            <div>
                <p class="text-sm font-semibold">Estado</p>
                <label>
                    <input type="radio" name="is_published" value="0" @checked(old('is_published',$post->is_published) == 0)>No publicado
                </label>
                <label>
                    <input type="radio" name="is_published" value="1" @checked(old('is_published',$post->is_published) == 1)>Publicado
                </label>
            </div>
            <div class="flex justify-end space-x-2">
                <flux:button type="submit" variant="primary">Actualizar</flux:button>
            </div>
        </div>
    </form>
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <script>
            const quill = new Quill('#editor', {
                theme: 'snow'
            });
            quill.on('text-change', function() {
                document.querySelector('#content').value = quill.root.innerHTML;
            });
        </script>

        <script
            src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous">
        </script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            // In your Javascript (external .js resource or <script> tag)
            $(document).ready(function() {
                $('#tags').select2({
                    tags: true,
                    tokenSeparators: [',', ' '],
                    placeholder: "Selecciona o agrega etiquetas",
                    allowClear: true,
                    theme: "classic"
                });
            });
        </script>
    @endpush
</x-layouts.admin>