<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;


#[Layout('components.layouts.app')]
class UsuarioController extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $user_id, $name, $email, $password, $password_confirmation, $telefono, $role_id;
    public $foto_perfil; // Ruta actual de la foto
    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|null */
    public $new_foto_perfil; // Para carga de nueva foto

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

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'role_id' => 'required|exists:roles,id',
            'telefono' => 'nullable|string|max:20',
            'new_foto_perfil' => 'nullable|image|max:3048', // Max 3MB
        ];

        // Reglas de contraseña
        if (!$this->user_id) {
            $rules['password'] = 'required|min:8|same:password_confirmation';
        } elseif ($this->password) {
            $rules['password'] = 'min:8|same:password_confirmation';
        }

        $this->validate($rules);

        $fotoPath = $this->foto_perfil;

        // Subir nueva foto si existe
        if ($this->new_foto_perfil) {
            if ($this->foto_perfil && Storage::disk('public')->exists($this->foto_perfil)) {
                Storage::disk('public')->delete($this->foto_perfil);
            }
            $fotoPath = $this->new_foto_perfil->store('usuarios', 'public');
        }

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'foto_perfil' => $fotoPath,
        ];

        // Validar si actualiza contraseña
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $role = Role::findById($this->role_id);

        if ($this->user_id) {
            $user = User::query()->findOrFail($this->user_id);
            $user->update($data);
            $user->syncRoles([$role]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Usuario actualizado exitosamente.']);
        } else {
            $user = User::query()->create($data);
            $user->assignRole($role);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Usuario creado exitosamente.']);
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $user = User::query()->findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->telefono = $user->telefono;
        $this->foto_perfil = $user->foto_perfil;
        
        $role = $user->roles->first();
        if ($role) {
            $this->role_id = $role->id;
        }

        $this->isModalOpen = true;
    }

    public function show($id)
    {
        $user = User::query()->findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->telefono = $user->telefono;
        $this->foto_perfil = $user->foto_perfil;
        $this->isViewModalOpen = true;
    }

    public function toggleStatus($id)
    {
        $user = User::query()->findOrFail($id);
        
        if ($user->id === auth()->id()) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'No puedes desactivar tu propio usuario.']);
            return;
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $estado = $user->is_active ? 'restaurado' : 'inactivado';
        $this->dispatch('swal', ['icon' => 'success', 'title' => "Usuario $estado correctamente."]);
    }

    public function removePhoto()
    {
        if ($this->foto_perfil && Storage::disk('public')->exists($this->foto_perfil)) {
            Storage::disk('public')->delete($this->foto_perfil);
            
            if ($this->user_id) {
                User::query()->find($this->user_id, ['*'])->update(['foto_perfil' => null]);
            }
            $this->foto_perfil = null;
        }
        $this->new_foto_perfil = null;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isViewModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->user_id = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->telefono = '';
        $this->role_id = '';
        $this->foto_perfil = null;
        $this->new_foto_perfil = null;
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::query()->with('roles')
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'asc')
            ->paginate(10);
            
        return view('livewire.usuario.usuario-index', [
            'users' => $users,
            'roles' => Role::query()->get(),
        ]);
    }
}
