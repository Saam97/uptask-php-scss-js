//Funcion auntoIniciada, que solo se ejecuta al invocar el archivo
(function () {

    ObtenerTareas();
    let tareas = [];
    let filtradas = [];


    //Boton para mostrar Modal de nueva tarea: 
        const nuevaTareaBtn = document.querySelector('#agregar-tarea');

        nuevaTareaBtn.addEventListener('click', function() {
            mostrarFormulario();
        });

        const filtros = document.querySelectorAll('#filtros input[type="radio"]')
        //iterar para saber cual ha sido selecionado
        filtros.forEach( radio => {
            radio.addEventListener('input', filtrarTareas);// filtrarTareas SIN EL () para enviar por defecto a e (evento)
        })

        function filtrarTareas(e){
            const filtro = e.target.value;

            if(filtro !== ''){
                filtradas = tareas.filter(tarea => tarea.estado === filtro);
                mostrarTareas();
            }else{
                filtradas = [];
                mostrarTareas();
            }

            

        }


        async function ObtenerTareas(){
            try {
                const id = obtenerProyecto();
                const url = `/api/tareas?id=${id}`;

                const respuesta = await fetch(url);//traer resultado(verificar status 200ok) (ACEPTA GET)
                const resultado = await respuesta.json();//pasar a json

                tareas = resultado.tareas;
                
                mostrarTareas();

                //console.log(resultado);    
                //console.log(tareas);
                
            } catch (error) {
                console.log(error);
            }
            
        }

        function mostrarTareas(){
            limpiarTareas();
            totalPendientes();
            totalCompletas();

            //si filtradas tiene algo, = a filtradas, si nó, = tareas
            const arrayTareas = filtradas.length ? filtradas : tareas;
            
            if(arrayTareas.length === 0){
                const contenedorTareas = document.querySelector('#listado-tareas');

                const textoNoTareas = document.createElement('LI');
                textoNoTareas.textContent = 'NO HAY TAREAS';
                textoNoTareas.classList.add('no-tareas');

                contenedorTareas.appendChild(textoNoTareas);
                return;
            }

            //diccionario para el btn estados
            const estados = {
                0: 'Pendiente',
                1: 'Completa'
            }

            arrayTareas.forEach(tarea => {
                const contenedorTarea = document.createElement('LI');
                contenedorTarea.dataset.tareaID = tarea.id; //Sacamos id del obj de cada tarea
                contenedorTarea.classList.add('tarea');

                const nombreTarea = document.createElement('P');
                nombreTarea.textContent = tarea.nombre;
                nombreTarea.ondblclick = function(){
                    mostrarFormulario(editar = true, {...tarea});
                }

                const opcionesDiv = document.createElement('DIV');
                opcionesDiv.classList.add('opciones');

                //Botones
                const btnEstadoTarea = document.createElement('BUTTON');
                btnEstadoTarea.classList.add('estado-tarea');
                btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
                btnEstadoTarea.textContent = estados[tarea.estado];
                btnEstadoTarea.dataset.estadoTarea = tarea.estado;//Atributo personalizado
                btnEstadoTarea.ondblclick = function(){
                    cambiarEstadoTarea(tarea);
                }

                const btnEliminarTarea = document.createElement('BUTTON');
                btnEliminarTarea.classList.add('eliminar-tarea');
                btnEliminarTarea.dataset.idTarea = tarea.id;
                btnEliminarTarea.textContent = 'Eliminar';
                btnEliminarTarea.ondblclick = function(){
                    confirmarEliminarTarea({...tarea});
                };

                //agregar al DIV
                opcionesDiv.appendChild(btnEstadoTarea);
                opcionesDiv.appendChild(btnEliminarTarea);

                //Agg al contenedor principal
                contenedorTarea.appendChild(nombreTarea);
                contenedorTarea.appendChild(opcionesDiv);

                //PEGARLO EN EL HTML el ID id="listado-tareas" de proyecto.php
                const listadoTareas = document.querySelector('#listado-tareas');
                listadoTareas.appendChild(contenedorTarea)
            });
            
        }

        function totalPendientes(){
            const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
            const pendienteRadio = document.querySelector('#pendientes');

            if(totalPendientes.length === 0){
                pendienteRadio.disabled = true;
            }else{
                pendienteRadio.disabled = false;
            }
        }

        function totalCompletas(){
            const totalCompletas = tareas.filter(tarea => tarea.estado === "1");
            const completasRadio = document.querySelector('#completadas');

            if(totalCompletas.length === 0){
                completasRadio.disabled = true;
            }else{
                completasRadio.disabled = false;
            }
        }

        function mostrarFormulario(editar = false, tarea = {}){//obj vacio para no de error el value 

            
            const modal = document.createElement('DIV'); //creamos div
            modal.classList.add('modal'); //asignamos clase

            //creamos el elemento html a mostrar 
            modal.innerHTML = `

            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar Nombre Tarea' : ' Añade una Nueva Tarea '} </legend>
                
                <div class="campo"> 
                    <label>Tarea</label>
                    <input 
                        type="text"
                        name="tarea"
                        placeholder="${tarea.nombre ? 'Editar la Tarea' : 'Añadir Tarea al Proyecto Actual'} "
                        id="tarea"
                        value="${tarea.nombre ? tarea.nombre : ''}"
                    />
                </div>

                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="${tarea.nombre ? 'Guardar Cambios' : 'añadir tarea'} "/>
                    <button class="cerrar-modal">Cancelar</button>
                </div>
            </form>
            
            `;

            //mostraremos la animacion de -30rem a -50, segun el css de modal
            setTimeout(() => {

                const formulario = document.querySelector('.formulario');
                formulario.classList.add('animar');

            },0);

            //Funcion para cerrar la ventana emergente (USANDO DELEGETION, ya que usamos innerHTML para crear la ventana)
            modal.addEventListener('click', function(e){
                e.preventDefault();
                
                //verificamos el click al Boton CERRAR
                if(e.target.classList.contains('cerrar-modal')){

                    //Para la Animacion
                    const formulario = document.querySelector('.formulario');
                    formulario.classList.add('cerrar');

                    //podemos solo poner modal.remove
                    setTimeout(() => {
                        modal.remove();//boton cerrar
                    },200);
                    
                }

                //verificar Boton, que no exita el campo vacio
                if(e.target.classList.contains('submit-nueva-tarea')){
                    const nombreTarea = document.querySelector('#tarea').value.trim();//capturar lo que esta en el id="tarea" (trim remueve los espacios al inicio)
                    
                    if(nombreTarea === ''){
                        //alerta, tipo, y lugar donde se mostrará
                        mostrarAlerta('El nombre de la tarea es Obligatorio', 'error', document.querySelector('.formulario legend'));
                        return;
                    }

                    if(editar){ //editar o agg
                        //le asignamos a la copia tarea el nombre que esta en el input
                        tarea.nombre = nombreTarea;
                        actualizarTarea(tarea);
                    }else{
                        agregarTarea(nombreTarea);
                    }
            
                }


            });

            //agg al html
            document.querySelector('.dashboard').appendChild(modal);
        }

         //Mostrar alerta en la interfaz
        function mostrarAlerta(mensaje, tipo, referencia){//referencia = a lugar donde se mostrará

                    //Evitar Mostrar muchas alertas
                    const alertaPrevia = document.querySelector('.alerta');
                    if(alertaPrevia){
                        alertaPrevia.remove();
                    }
        
                    const alerta = document.createElement('DIV');
                    alerta.classList.add('alerta', tipo);
                    alerta.textContent = mensaje;
        
                    //referencia.appendChild(alerta); //lo agg dentro de la referencia, dentro( div, legent h1 et)
                    //agregar antes de una etiqueta, en este caso un legend
                    //referencia.parentElement SABE CUAL la clase padre
                    referencia.parentElement.insertBefore(alerta, referencia);//identifica la clase padre, recibe el nuevo elemento y el elemento hijo. lo nuevo se insertará entre estos 2s
                    //para despues del elemento referencia.netxElementSibling
        
                    //quitar alerta
                    setTimeout(() => {
                        alerta.remove();
                    }, 5000);
                   
        }
        
        //_------------------------------------------------

        //consultar server para agg tarea actual (API)
        async function agregarTarea(tarea){ //la tarea es el campo input que habiamos creado antes
            //1. Construir la Peticion
            const datos = new FormData();
            
            //Datos de el modelo o DB
            datos.append('nombre', tarea);
            datos.append('proyectoid', obtenerProyecto());

            //enviar peticion
            try {
                const url = 'http://localhost:4000/api/tarea';//url para hacer peticion
                const respuesta = await fetch(url, { //pasamos url y objeto a consultar
                    method: 'POST',//metodo
                    body: datos //datos del FormData
                })

                //para obtener la respuesta
                const resultado = await respuesta.json();

                //mostrar alerta
                mostrarAlerta(resultado.mensaje, resultado.tipo , document.querySelector('.formulario legend'));//mostrar antes del formulario y antes del legend
                
                //en caso de exito cerrar ventana
                if (resultado.tipo === 'exito') {
                    const modal = document.querySelector('.modal');

                    setTimeout(() => {
                        modal.remove();    
                    }, 2000);

                    //Agg el objeto de tarea al Global de tareas (LOS MISMOS PARAMETROS DE LA INF DE LA API)
                    const tareaObj = {
                        id: String(resultado.id),
                        nombre: tarea,
                        estado: "0",
                        proyectoid: resultado.proyectoid
                    }

                    //creamos una copia de tarea y le agg el nuevo obj tareaobj
                    tareas = [...tareas, tareaObj];
                    mostrarTareas();
                    
                }


            } catch (error) {
                console.log(error);
            }
        }

        function cambiarEstadoTarea(tarea){
        
            const nuevoEstado = tarea.estado === "1" ? "0" : "1";
            tarea.estado = nuevoEstado;
            actualizarTarea(tarea);
        }

        async function actualizarTarea(tarea){
            //aplicamos destruccion
            const {estado, id, nombre, proyectoid} = tarea;

            const datos = new FormData();
            
            datos.append('id', id);
            datos.append('nombre', nombre);
            datos.append('estado', estado);

            //obtener la url y ver si es la persona que lo creó
            datos.append('proyectoid', obtenerProyecto());

            //mostrar datos al dar click en el boton, cambiar de estado en la consola
            // for(let valor of datos.values()){
            //     console.log(valor);
            // }

            try {
                const url = 'http://localhost:4000/api/actualizar';

                const respuesta = await fetch(url, { //ACEPTA POST
                    //creamos el objeto de configuracion a enviar
                    method: 'POST',
                    body: datos

                });

                const resultado = await respuesta.json(); //pasamos a respuesta a json

                if(resultado.respuesta.tipo === 'exito'){ //son las variables del resultado
                    //mostrarAlerta(resultado.respuesta.mensaje, resultado.respuesta.tipo, document.querySelector('.listado-tareas'));
                    Swal.fire(
                        resultado.respuesta.mensaje,
                        resultado.respuesta.mensaje,
                        'success'
                    );
                    
                    //cerrar ventana
                    const modal = document.querySelector('.modal');
                    if(modal){
                        modal.remove();
                    }
                    

                    //guardamos la tarea que fue modificada, su estado
                    tarea = tareas.map(TareaMemoria => {
                        //id que estamos modificando para el vitualDoom
                        if(TareaMemoria.id === id){
                            TareaMemoria.estado = estado;
                            TareaMemoria.nombre = nombre;
                        }

                        //retornamos el estado de la tarea y se actualiza en la web
                        return TareaMemoria;
                    });

                    mostrarTareas();
                }

            } catch (error) {
                console.log(error);
            }

        }

        function confirmarEliminarTarea(tarea){

            Swal.fire({
                title: "Eliminar Terea?",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelbuttonText: 'NO'
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                  eliminarTarea(tarea);
                } 
              });
        }

        async function eliminarTarea(tarea){
      
            //aplicamos destruccion
            const {estado, id, nombre} = tarea;
            const datos = new FormData();
            datos.append('id', id);
            datos.append('nombre', nombre);
            datos.append('estado', estado);

            //obtener la url y ver si es la persona que lo creó
            datos.append('proyectoid', obtenerProyecto());            

            try {
                const url = 'http://localhost:4000/api/eliminar';
                const respuesta = await fetch(url, { //ACEPTA POST
                    //creamos el objeto de configuracion a enviar
                    method: 'POST',
                    body: datos
                })

                const resultado = await respuesta.json(); //pasamos a respuesta a json

                if(resultado.resultado){ //son las variables del resultado
                    // mostrarAlerta(
                    // resultado.mensaje,
                    // resultado.tipo,
                    // document.querySelector('.listado-tareas'));

                    Swal.fire('Eliminado!', resultado.mensaje, 'success');

                    //Usamos filter en vez de map
                    tareas = tareas.filter( tareaMemoria => tareaMemoria.id !== tarea.id);
                    mostrarTareas();    
                }                

            
                
            } catch (error) {
                
            }
        }

        function obtenerProyecto(){

            //saber el id de la tarea que está en la url
            const proyectoParametros = new URLSearchParams(window.location.search);//capturar datos de la url (es un obj)

            //iterar y extraer lo que necesitams del obj
            const proyecto = Object.fromEntries(proyectoParametros.entries());
            return proyecto.id;//sale del 'proyectoid' de agg tareas
            //return proyectoParams.get('url');
        }


        function limpiarTareas(){
            const listadoTareas = document.querySelector('#listado-tareas');
            
            //elimina el contenido 1 por 1, es mas rapido
            while(listadoTareas.firstChild){
                listadoTareas.removeChild(listadoTareas.firstChild);
            }
        }

})();