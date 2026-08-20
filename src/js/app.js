let paso = 1;

const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    nombre: "",
    fecha: "",
    hora: "",
    usuario_id: "",
    servicios: []
}

document.addEventListener('DOMContentLoaded',function ( ){
    iniciarApp();
});

function iniciarApp() {
    
    mostrarSeccion(); //Muestra y oculta las secciones
    tabs(); //Cambia las secciones cuando se presionen los tabs
    botonPaginador(); //Botones que cambian de página
    
    paginaSiguiente();
    paginaAnterior();
    
    consultarApiServicios(); //Consulta los servicios en el backend de php
    
    idCliente();
    nombreCliente();
    fechaCliente();
    horaCliente();
    
    muestraResumen();
    
}

function tabs() {
    
    const botones = document.querySelectorAll(".tabs button");
    
    botones.forEach( (boton) => {
        
        boton.addEventListener('click',function(e) {
            
            paso = parseInt(e.target.dataset.paso);
            
            mostrarSeccion();
            botonPaginador();
            
        });
        
    });
    
}

function mostrarSeccion() {
    
    const seccionAnterior = document.querySelector(".mostrar");
    if(seccionAnterior) {
        seccionAnterior.classList.remove("mostrar");
    }
    
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add("mostrar");
    
    const tabAnterior = document.querySelector(".actual");
    if(tabAnterior) {
        tabAnterior.classList.remove("actual");
    }
    
    //Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add("actual");
    
}

function botonPaginador() {
    
    const anterior = document.querySelector("#anterior");
    const siguiente = document.querySelector("#siguiente");
    
    if(paso === 1) {
        
        anterior.classList.add("ocultarboton");
        siguiente.classList.remove("ocultarboton");
        
    } else if(paso === 3) {
        
        anterior.classList.remove("ocultarboton");
        siguiente.classList.add("ocultarboton");
        muestraResumen();
        
    } else {
        
        anterior.classList.remove("ocultarboton");
        siguiente.classList.remove("ocultarboton");
        
    }
    
    mostrarSeccion();
    
}

function paginaAnterior() {
    
    const anterior = document.querySelector("#anterior");
    anterior.addEventListener('click', function () {
        if(paso <= pasoInicial) {
            return;
        }
        paso--;
        botonPaginador();
    });
    
}

function paginaSiguiente() {
    
    const psiguiente = document.querySelector("#siguiente");
    psiguiente.addEventListener('click', function () {
        if(paso >= pasoFinal) {
            return;
        }
        paso++;
        botonPaginador();
    });
    
}

async function consultarApiServicios() {
    
    try {
        
        //const url = `${location.origin}/api/servicios`;
        const url = "/api/servicios";
        
        const respuesta = await fetch(url)
        const servicios = await respuesta.json();
        
        mostrarServicios(servicios);
        
    } catch (error) {
        
        console.log(error);
        
    }
    
}

function mostrarServicios(servicios) {
    
    servicios.forEach( servicio => {
        
        const { id, nombre, precio } = servicio
        
        const nombreServicio = document.createElement("P");
        nombreServicio.classList.add("nombre-servicio");
        nombreServicio.textContent = nombre;
        
        const precioServicio = document.createElement("P");
        precioServicio.classList.add("precio-servicio");
        precioServicio.textContent = `$${precio}`;
        
        const servicioDiv = document.createElement("DIV");
        servicioDiv.classList.add("servicio");
        servicioDiv.dataset.servicioId = id;
        
        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        }
        
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        
        document.querySelector("#servicios").appendChild(servicioDiv);
        
    } );
    
}

function seleccionarServicio(servicio) {
    
    const {id} = servicio;
    const { servicios } = cita;
    
    //Identificar al elemento al que se le da click
    const divServicio = document.querySelector(`[data-servicio-id="${id}"]`);
    
    if( servicios.some( existe => existe.id === id )) {
        
        cita.servicios = servicios.filter(existe => existe.id !== id); //filter nos permite sacar un elemento basado en cierta condicion
        divServicio.classList.remove("seleccionado");
        
    }else{
        
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add("seleccionado");
        
    }
    
}

function idCliente() {
    
    cita.usuario_id = document.querySelector("#usuario_id").value;
    
}

function nombreCliente() {
    
    cita.nombre = document.querySelector("#nombre").value;
    
}

function fechaCliente() {
    
    const fecha = document.querySelector("#fecha");
    //fecha.min = new Date().toISOString().slice(0,10);
    
    fecha.addEventListener('change',function(e) {
        
        const dia = new Date(e.target.value).getUTCDay(); //UTCDay hora en México
        
        if([0,6].includes(dia)) {
            mostrarAlerta("error","Fines de semana no permitidos",".formulario");
            e.target.value = "";
            cita.fecha = "";
            
        }else{
            cita.fecha = e.target.value;
        }
        
    });
    
}



function horaCliente() {
    
    const hora = document.querySelector("#hora");
    
    hora.addEventListener('change',function(e) {
        
        const horaCita = e.target.value.split(":")[0];
        
        if(horaCita < 11 || horaCita > 20) {
            
            mostrarAlerta("error","La hora no es valida 11am-20pm",".formulario");
            e.target.value = "";
            cita.hora = "";
            
        }else{
            
            cita.hora = e.target.value;
            
        }
        
    });
    
}

function mostrarAlerta(tipo,mensaje,elemento,desaparece = true) {
    
    const alertaPrevia = document.querySelector(".alerta");
    if(alertaPrevia){
        alertaPrevia.remove();
    }
    
    const contenedor = document.querySelector(elemento);
    const div = document.createElement("DIV");
    div.classList.add("alerta",tipo);
    div.textContent = mensaje
    
    contenedor.appendChild(div);
    
    if(desaparece) {
        
        setTimeout(() => {
            
            div.remove();
            
        },5000);
        
    }
    
}

function muestraResumen() {
    
    const resumen = document.querySelector(".contenido-resumen");
    
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }
    
    if(Object.values(cita).includes("") || cita.servicios.length === 0){
        mostrarAlerta("error","Por favor, llena todos los campos",".contenido-resumen", false);
        return;
    }
    
    //Agregando el título y descripción
    const resumenCita = document.createElement("H2");
    resumenCita.textContent = "Resumen de cita";
    
    const descripcionCita = document.createElement("P");
    descripcionCita.classList.add("text-center");
    descripcionCita.textContent = "Verifica que los datos sean correcta";
    descripcionCita.style.borderBottom = "1px solid #e1e1e1";
    descripcionCita.style.paddingBottom = "15px";
    
    const { nombre, fecha, hora, servicios } = cita;
    
    //Agregando el nombre
    const campoNombre = document.createElement("P");
    campoNombre.innerHTML = `<span>Nombre: </span> ${nombre}`;
     
    //Formatear la fecha en español
    const fechaObj = new Date(fecha + "T00:00:00");

    const opciones = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric"
    };

    const fechaFormateada = fechaObj.toLocaleDateString('es-MX', opciones);
    
    //Agregando la fecha
    const campoFecha = document.createElement("P");
    const partesFecha = fecha.split("-");
    campoFecha.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;
    
    //Agregando la hora
    const campoHora = document.createElement("P");
    campoHora.innerHTML = `<span>Hora:</span> ${hora} Horas`;
    
    resumen.appendChild(resumenCita);
    resumen.appendChild(descripcionCita);
    resumen.appendChild(campoNombre);
    resumen.appendChild(campoFecha);
    resumen.appendChild(campoHora);
    
    //Agregando el resumen de servicios
    const resumenServicio = document.createElement("H2");
    resumenServicio.textContent = "Resumen de servicios";
    
    const descripcionServicio = document.createElement("P");
    descripcionServicio.classList.add("text-center");
    descripcionServicio.textContent = "Verifica que los servicios agregados sean correctos";
    
    resumen.appendChild(resumenServicio);
    resumen.appendChild(descripcionServicio);
    
    //Agregando los servicios
    servicios.forEach( servicio => {
        
        const {id, nombre, precio} = servicio;
        
        const contenedorServicio = document.createElement("DIV");
        contenedorServicio.classList.add("contenedor-servicio");
        
        const nombreServicio = document.createElement("P");
        nombreServicio.innerHTML = `${nombre}`;
        
        const precioServicio = document.createElement("P");
        precioServicio.innerHTML = `<span>Precio:</span> ${precio}`;
        
        contenedorServicio.appendChild(nombreServicio);
        contenedorServicio.appendChild(precioServicio);
        
        resumen.appendChild(contenedorServicio);
        
    });
    
    //Botón para reservar
    const boton = document.createElement("BUTTON");
    boton.classList.add("boton-azul");
    boton.textContent = "Reservar cita";
    boton.onclick = reservarCita;
    resumen.appendChild(boton);
    
}

async function reservarCita() {
    
    const {fecha, hora, servicios, usuario_id} = cita;
    
    const servicio_id = servicios.map( servicio => servicio.id);
    
    const formulario = new FormData;
    
    formulario.append("fecha",fecha);
    formulario.append("hora",hora);
    formulario.append("usuario_id",usuario_id);
    formulario.append("servicio_id",servicio_id);
    
    try {
        
        //const url = `${location.origin}/api/citas`;
        const url = "/api/citas";
        
        const respuesta = await fetch(url,{
            method: "POST",
            body: formulario
        });
        
        const resultado = await respuesta.json();

        console.log(resultado);
        
        if(resultado.resultado) {
            
            Swal.fire({
                title: "Cita creada!",
                text: "Tu cita fue creada correctamente!",
                icon: "success",
                button: "OK"
            })
            .then(function () {
                
                // setTimeout( function () {
                    
                    window.location.reload(); //Recargar la página
                    
                // }, 1000 );
                
            });
            
        }
        
    } catch (error) {
        Swal.fire({
            title: "Error!",
            text: "Hubo un error al momento de crear la cita, intentelo más tarde !",
            icon: "error"
        })
        .then(function () {
            window.location.reload();
        });
    }
    
}
