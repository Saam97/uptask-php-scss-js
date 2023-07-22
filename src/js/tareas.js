//Funcion auntoIniciada, que solo se ejecuta al invocar el archivo
(function () {

    //Boton para mostrar Modal de nueva tarea: 
        const nuevaTareaBtn = document.querySelector('#agregar-tarea');
        nuevaTareaBtn.addEventListener('click', mostrarFormulario);

        function mostrarFormulario(){
            const modal = document.createElement('DIV'); //creamos div
            modal.classList.add('modal'); //asignamos clase

            //creamos el elemento html a mostrar 
            modal.innerHTML = `

            <form class="formulario nueva-tarea">
                <legend>Añade una Nueva Tarea</legend>
                
                <div class="campo"> 
                    <label>Tarea</label>
                    <input 
                        type="text"
                        name="tarea"
                        placeholder="Añadir Tarea al Proyecto Actual"
                        id="tarea"
                    />
                </div>

                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="añadir tarea"/>
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

                //verificar Boton AGREGAR Tarea
                if(e.target.classList.contains('submit-nueva-tarea')){
                    submitFormularioNuevaTarea();
                }
            });

            //agg al html
            document.querySelector('.dashboard').appendChild(modal);
        }

        function submitFormularioNuevaTarea(){
            const tarea = document.querySelector('#tarea').value.trim();//capturar lo que esta en el id="tarea" (trim remueve los espacios al inicio)
            
            if(tarea === ''){
                //alerta, tipo, y lugar donde se mostrará
                mostrarAlerta('El nombre de la tarea es Obligatorio', 'error', document.querySelector('.formulario legend'));
                return;
            }

            //En caso que tenga un nombre
             agregarTarea(tarea);
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
            }, 3000);
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
                
            } catch (error) {
                console.log(error);
            }
        }


        function obtenerProyecto(){

            //saber el id de la tarea que está en la url
            const proyectoParametros = new URLSearchParams(window.location.search);//capturar datos de la url (es un obj)

            //iterar y extraer lo que necesitams del obj
            const proyecto = Object.fromEntries(proyectoParametros.entries());
            return proyecto.id;
        }

})();