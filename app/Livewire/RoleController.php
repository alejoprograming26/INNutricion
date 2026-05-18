<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class RoleController extends Component
{
    use WithPagination;

    public $search = '';
    public $role_id, $name;
    
    public $isModalOpen = false;
    public $isViewModalOpen = false;
    public $isPermisosModalOpen = false;

    public $permisosAgrupados = [];
    public $permisosAsignados = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        abort_if(!auth()->user()->can('Crear Rol'), 403, 'No tienes permiso para crear roles.');
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->name = mb_strtoupper($this->name, 'UTF-8');

        $this->validate([
            'name' => ['required', 'string', 'max:125', \Illuminate\Validation\Rule::unique('roles', 'name')->ignore($this->role_id)],
        ]);

        if ($this->role_id) {
            abort_if(!auth()->user()->can('Editar Rol'), 403, 'No tienes permiso para editar roles.');
            $role = Role::find($this->role_id);
            $role->update(['name' => $this->name]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Rol actualizado correctamente.']);
        } else {
            abort_if(!auth()->user()->can('Crear Rol'), 403, 'No tienes permiso para crear roles.');
            Role::create(['name' => $this->name, 'guard_name' => 'web']);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Rol creado correctamente.']);
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('Editar Rol'), 403, 'No tienes permiso para editar roles.');
        $this->resetInputFields();
        $role = Role::findOrFail($id);
        $this->role_id = $role->id;
        $this->name = $role->name;
        $this->isModalOpen = true;
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        $this->role_id = $role->id;
        $this->name = $role->name;
        $this->isViewModalOpen = true;
    }

    public function delete($id)
    {
        abort_if(!auth()->user()->can('Eliminar Rol'), 403, 'No tienes permiso para eliminar roles.');
        $role = Role::findOrFail($id);
        
        // Verificar si el rol tiene usuarios asignados
        if ($role->users()->exists()) {
            $this->dispatch('swal', [
                'icon' => 'error', 
                'title' => 'No se puede eliminar el rol.',
                'text' => 'Este rol tiene usuarios asignados. Primero cambia el rol de esos usuarios.'
            ]);
            return;
        }

        $role->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Rol eliminado correctamente.']);
    }

    public function asignarPermisos($id)
    {
        abort_if(!auth()->user()->can('Editar Rol'), 403, 'No tienes permiso para asignar permisos a roles.');
        $role = Role::findOrFail($id);
        $this->role_id = $role->id;
        $this->name = $role->name;
        
        $this->permisosAsignados = $role->permissions->pluck('name')->toArray();
        
        $this->permisosAgrupados = \Spatie\Permission\Models\Permission::all()->groupBy(function ($permission) {
            if(stripos($permission->name, 'Inicio') !== false) { return 'Dashboard';}
            elseif(stripos($permission->name, 'Ajustes') !== false) { return 'Configuración';}
            elseif(stripos($permission->name, 'Rol') !== false) { return 'Roles del Sistema';}
            elseif(stripos($permission->name, 'Usuario') !== false) { return 'Usuarios del Sistema';}
            elseif(stripos($permission->name, 'Sector') !== false) { return 'Sectores';}
            elseif(stripos($permission->name, 'Comuna') !== false) { return 'Comunas';}
            elseif(stripos($permission->name, 'Meta') !== false) { return 'Metas';}
            elseif(stripos($permission->name, 'Transcripcion') !== false) { return 'Transcripciones';}
            elseif(stripos($permission->name, 'Abordaje') !== false || 
                   stripos($permission->name, 'Escuela') !== false ||
                   stripos($permission->name, 'Liderazgo') !== false ||
                   stripos($permission->name, 'Diversidad') !== false ||
                   stripos($permission->name, 'Circulo') !== false ||
                   stripos($permission->name, 'Vulnerabilidad') !== false ||
                   stripos($permission->name, 'Feria') !== false) { return 'Actividades';}
            elseif(stripos($permission->name, 'Calendario') !== false) { return 'Calendario';}
            return 'Otros';
        })->toArray();

        $this->isPermisosModalOpen = true;
    }

    public function updatePermisos()
    {
        abort_if(!auth()->user()->can('Editar Rol'), 403, 'No tienes permiso para editar permisos de roles.');
        $role = Role::findOrFail($this->role_id);
        $role->syncPermissions($this->permisosAsignados);
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Permisos actualizados correctamente.']);
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isViewModalOpen = false;
        $this->isPermisosModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->role_id = null;
        $this->name = '';
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'asc')
            ->paginate(10);
            
        return view('livewire.admin.rol.rol-index', [
            'roles' => $roles
        ]);
    }
}
