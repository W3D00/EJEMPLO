<x-layouts.admin>
    <div class="mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('admin.dashboard')}}">Dahsboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{route('admin.users.index')}}">Users</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Nuevo</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
    <form action="{{route('admin.users.store')}}" method="POST" class="bg-white px-6 py-8 rounded-lg shadow-lg space-y-4">
        @csrf
        <flux:input name="name" label="Nombre" value="{{old('name')}}" />
        <flux:input type="email" name="email" label="Email" value="{{old('email')}}" />
        <flux:input type="password" name="password" label="Contraseña" />
        <flux:input type="password" name="password_confirmation" label="Confirmar contraseña" />
        <div>
            <p class="text-sm font-medium mb-1">Seleccione el rol:</p>
            <ul>
                @foreach ($roles as $role)
                    <li>
                        <label class="flex items-center">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked(in_array($role->id, old('roles', []))) class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <span class="ml-1">
                                <label for="role-{{ $role->id }}" class="text-sm">{{ $role->name }}</label>
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