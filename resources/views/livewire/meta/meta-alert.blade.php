<div x-data="{
    init() {
        Livewire.on('meta-achieved', (event) => {
            const data = event[0];
            
            setTimeout(() => {
                window.Swal.fire({
                    title: '¡Meta Cumplida!',
                    html: `
                        <div class='flex flex-col items-center space-y-4'>
                            <div class='p-4 rounded-full bg-opacity-20 flex items-center justify-center' style='background-color: ${data.color}33'>
                                <svg xmlns='http://www.w3.org/2000/svg' class='h-12 w-12' fill='none' viewBox='0 0 24 24' stroke='${data.color}' stroke-width='2'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' />
                                </svg>
                            </div>
                            <div class='text-center'>
                                <p class='text-lg font-semibold text-zinc-800 dark:text-zinc-200'>El municipio <span style='color: ${data.color}'>${data.municipio}</span> ha alcanzado el 100% de su meta anual.</p>
                                <p class='text-sm text-zinc-500 mt-2'>¡Excelente trabajo en la recolección de datos!</p>
                            </div>
                        </div>
                    `,
                    confirmButtonText: '¡Excelente!',
                    confirmButtonColor: data.color,
                    background: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#f4f4f5' : '#18181b'
                });
            }, 2000);
        });
    }
}">
</div>
