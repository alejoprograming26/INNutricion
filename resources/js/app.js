import './bootstrap';
import Swal from 'sweetalert2';
window.Swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

window.Toast = Toast;

document.addEventListener('livewire:initialized', () => {
    Livewire.on('swal', (event) => {
        // En Livewire 3, el array que envias en PHP llega como event[0]
        const data = event[0]; 
        Toast.fire({
            icon: data.icon || 'success',
            title: data.title
        });
    });
});
