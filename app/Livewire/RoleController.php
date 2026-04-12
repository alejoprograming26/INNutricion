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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
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
            $role = Role::find($this->role_id);
            $role->update(['name' => $this->name]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Rol actualizado correctamente.']);
        } else {
            Role::create(['name' => $this->name, 'guard_name' => 'web']);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Rol creado correctamente.']);
        }

        $this->closeModal();
    }

    public function edit($id)
    {
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

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isViewModalOpen = false;
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
