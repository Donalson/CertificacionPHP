<template>
    <div><br><br>
        <h3 class="text-center">Comidas</h3>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#FormularioCrear">
          Registrar Comida
        </button><br><br>

        <div class="modal fade" id="FormularioCrear" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Formulario de Comida</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form @submit.prevent="EditarComida(comida)" v-if="ModoEdicion" enctype="multipart/form-data">
                            <h3>Editar Comida</h3>

                            <input type="text" class="form-control mb-2" placeholder="Nombre" v-model="comida.Nombre" required maxlength="50" minlength="3">                
                            <input type="number" step="0.01" class="form-control mb-2" placeholder="Precio" v-model="comida.Precio" required minlength="2">
                            <input type="file" name="Foto" @change="TomarImagen" accept="image/*" ref="fileupload">
                            
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Editar</button>
                                <button type="submit" class="btn btn-danger" data-dismiss="modal" @click="cancelarEdicion">Cancelar</button>
                            </div>
                        </form>
                            
                        <form @submit.prevent="Agregar" v-else enctype="multipart/form-data">
                            <h3>Agregar Comida</h3>

                            <input type="text" class="form-control mb-2" placeholder="Nombre" v-model="comida.Nombre" required maxlength="50" minlength="3">                
                            <input type="number" step="0.01" class="form-control mb-2" placeholder="Precio" v-model="comida.Precio" required minlength="2">
                            <input type="file" name="Foto" @change="TomarImagen" accept="image/*" ref="fileupload">
                            <div class="modal-footer">                            
                                <button type="submit" class="btn btn-success">Agregar</button>
                                <button type="button" class="btn btn-danger" @click="cancelarEdicion" data-dismiss="modal">Cancelar</button>
                            </div>
                        </form>

                    </div>                
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in comidas" :key="index">                        
                        <td class="table-info">{{ item.id}}</td>                        
                        <td class="table-info">
                            <img :src="'storage/' + item.Foto" alt="FotoComida" width="100" height="100">
                        </td>
                        <td class="table-info">{{ item.Nombre }}</td>
                        <td class="table-info">{{ item.Precio}}</td>
                        <td class="table-info">
                            <button class="btn btn-warning" @click="EditarFormulario(item)" data-toggle="modal"  data-target="#FormularioCrear">Editar</button>
                            <button class="btn btn-danger" @click="EliminarComida(item, index)">Eliminar</button>                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>  

    </div>
</template>

<script>
export default {
    data() {
    return {
      //Intanciamos los arreglos y variables que vallamos a utilizar
      comidas: [],
      ModoEdicion: false,      
      comida: {
          Nombre: '', 
          Precio: '', 
          Foto: '',
          }
    }
  },
  created(){
    //Obtenemos los datos del controlador
    axios.get('/datoscomidas').then(res=>{
      this.comidas = res.data;
    })
  },
  methods:{
    Agregar(){      
      var params = new  FormData();
      params.append('Nombre', this.comida.Nombre);
      params.append('Precio', this.comida.Precio);
      params.append('Foto', this.comida.Foto)
      axios.post('/comidas', params)
        .then((res) =>{   
          const datosrecibidos = res.data;
          this.comidas.push(datosrecibidos);
          this.cancelarEdicion();
        }).catch(error=>{
              alert(error)
            })
    },
    EditarFormulario(item){
      this.ModoEdicion = true;
      this.comida.id = item.id;
      this.comida.Nombre = item.Nombre;
      this.comida.Precio = item.Precio;
    },
    EditarComida(item){      
      var edit = new FormData();
      edit.append('Nombre', this.comida.Nombre);
      edit.append('Precio', this.comida.Precio);
      edit.append('Foto', this.comida.Foto);
      edit.append('_method', 'PUT');
      edit.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      axios.post(`/comidas/${item.id}`, edit)
        .then(res=>{
          const index = this.comidas.findIndex(item => item.id === res.data.id);
          this.comidas[index] = res.data;
          this.cancelarEdicion();
        }).catch(error=>{
              alert(error)
            })
    },
    EliminarComida(item, index){
      const confirmacion = confirm(`Eliminar Comida ${item.Nombre}`);
      if(confirmacion){
        axios.delete(`/comidas/${item.id}`)
          .then(()=>{
            this.comidas.splice(index, 1);
          }).catch(error=>{
              alert(error)
            })
      }
    },
    cancelarEdicion(){
      this.ModoEdicion = false;
      this.comida = {
          Nombre: '', 
          Precio: '', 
          Foto: '',          
          };
      const input = this.$refs.fileupload;
      input.type = 'text';
      input.type = 'file';
      $('#FormularioCrear').modal('hide');
    },
    TomarImagen(e){
        this.comida.Foto = event.target.files[0];
    },
  }
}
</script>