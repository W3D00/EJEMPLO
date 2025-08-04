<x-layouts.admin>
    <div class="mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('admin.dashboard')}}">Dahsboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{route('admin.roles.index')}}">Roles</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Nuevo</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <form action="{{route('admin.roles.store')}}" method="POST" class="bg-white px-6 py-8 rounded-lg shadow-lg space-y-4">
        @csrf
        <flux:input name="name" label="Nombre" value="{{old('name')}}" />

        <div>
            <p class="text-sm font-medium mb-1">Seleccione los permisos para este rol:</p>
            <ul>
                @foreach ($permissions as $permission)
                    <li>
                        <label class="flex items-center">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked(in_array($permission->id, old('permissions', []))) class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <span class="ml-1">
                                <label for="permission-{{ $permission->id }}" class="text-sm">{{ $permission->name }}</label>
                            </span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">Guardar</flux:button>
        </div>
    </form>

</x-layouts.admin>