<template>
    <div><br><br>
        <h3 class="text-center">Bebidas</h3>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#FormularioCrear">
          Registrar Bebida
        </button><br><br>

        <div class="modal fade" id="FormularioCrear" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Formulario de Bebida</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form @submit.prevent="EditarBebida(bebida)" v-if="ModoEdicion" enctype="multipart/form-data">
                            <h3>Editar Bebida</h3>

                            <input type="text" class="form-control mb-2" placeholder="Nombre" v-model="bebida.Nombre" required maxlength="50" minlength="3">                
                            <input type="number" step="0.01" class="form-control mb-2" placeholder="Precio" v-model="bebida.Precio" required minlength="2">
                            <input type="file" name="Foto" @change="TomarImagen" accept="image/*" ref="fileupload">
                            
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Editar</button>
                                <button type="submit" class="btn btn-danger" data-dismiss="modal" @click="cancelarEdicion">Cancelar</button>
                            </div>
                        </form>
                            
                        <form @submit.prevent="Agregar" v-else enctype="multipart/form-data">
                            <h3>Agregar Bebida</h3>

                            <input type="text" class="form-control mb-2" placeholder="Nombre" v-model="bebida.Nombre" required maxlength="50" minlength="3">                
                            <input type="number" step="0.01" class="form-control mb-2" placeholder="Precio" v-model="bebida.Precio" required minlength="2">
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
                    <tr v-for="(item, index) in bebidas" :key="index">                        
                        <td class="table-info">{{ item.id}}</td>                        
                        <td class="table-info">
                            <img :src="'storage/' + item.Foto" alt="FotoComida" width="100" height="100">
                        </td>
                        <td class="table-info">{{ item.Nombre }}</td>
                        <td class="table-info">{{ item.Precio}}</td>
                        <td class="table-info">
                            <button class="btn btn-warning" @click="EditarFormulario(item)" data-toggle="modal"  data-target="#FormularioCrear">Editar</button>
                            <button class="btn btn-danger" @click="EliminarBebida(item, index)">Eliminar</button>                            
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
      bebidas: [],
      ModoEdicion: false,      
      bebida: {
          Nombre: '', 
          Precio: '', 
          Foto: '',
          }
    }
  },
  created(){
    //Obtenemos los datos del controlador
    axios.get('/datosbebidas').then(res=>{
      this.bebidas = res.data;
    })
  },
  methods:{
    Agregar(){      
      var params = new  FormData();
      params.append('Nombre', this.bebida.Nombre);
      params.append('Precio', this.bebida.Precio);
      params.append('Foto', this.bebida.Foto)
      axios.post('/bebidas', params)
        .then((res) =>{   
          const datosrecibidos = res.data;
          this.bebidas.push(datosrecibidos);
          this.cancelarEdicion();
        }).catch(error=>{
              alert(error)
            })
    },
    EditarFormulario(item){
      this.ModoEdicion = true;
      this.bebida.id = item.id;
      this.bebida.Nombre = item.Nombre;
      this.bebida.Precio = item.Precio;
    },
    EditarBebida(item){      
      var edit = new FormData();
      edit.append('Nombre', this.bebida.Nombre);
      edit.append('Precio', this.bebida.Precio);
      edit.append('Foto', this.bebida.Foto);
      edit.append('_method', 'PUT');
      edit.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      axios.post(`/bebidas/${item.id}`, edit)
        .then(res=>{
          const index = this.bebidas.findIndex(item => item.id === res.data.id);
          this.bebidas[index] = res.data;
          this.cancelarEdicion();
        }).catch(error=>{
              alert(error)
            })
    },
    EliminarBebida(item, index){
      const confirmacion = confirm(`Eliminar Bebida ${item.Nombre}`);
      if(confirmacion){
        axios.delete(`/bebidas/${item.id}`)
          .then(()=>{
            this.bebidas.splice(index, 1);
          }).catch(error=>{
              alert(error)
            })
      }
    },
    cancelarEdicion(){
      this.ModoEdicion = false;
      this.bebida = {
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
        this.bebida.Foto = event.target.files[0];
    },
  }
}
</script>