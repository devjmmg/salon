document.addEventListener('DOMContentLoaded',function () {
    iniciarApp();
});

function iniciarApp() {
    filtrarFecha();
}

async function filtrarFecha() {
    
    const fecha = document.querySelector("#fecha");
    if ( fecha ) {
        fecha.addEventListener('change',function (e) {
        
            if( e.target.value === "") {
                
                const hoy = new Date().toISOString().split("T")[0];
                window.location = `?fecha=${hoy}`;
                
            }else{
                window.location = `?fecha=${e.target.value}`;
            }
            
        });
    }
    
    
}