<template>
    <div><br><br>
        <h3 class="text-center">Usuarios</h3>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#FormularioCrear">
          Crear Usuario
        </button><br><br>

        <div class="modal fade" id="FormularioCrear" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Formulario de Usuarios</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form @submit.prevent="editarusuario(usuario)" v-if="modoEditar" enctype="multipart/form-data">
                            <h3>Editar Usuario</h3>

                            <input type="text" class="form-control mb-2" placeholder="Nombre" v-model="usuario.Nombre" required maxlength="30" minlength="3">                
                            <input type="text" class="form-control mb-2" placeholder="Apellido" v-model="usuario.Apellido" required maxlength="50" minlength="4">
                            <select class="form-control mb-2" v-model="usuario.Categoria" required>
                                <option :value="usuario.Categoria" selected>Categorias</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Dueño">Dueño</option>
                                <option value="Empleado">Empleado</option>
                            </select>
                            <input type="text" class="form-control mb-2" placeholder="Telefono" v-model="usuario.Telefono" maxlength="9" minlength="2">
                            <input type="text" class="form-control mb-2" placeholder="DPI" v-model="usuario.DPI" maxlength="15" minlength="2">
                            <input type="date" class="form-control mb-2" placeholder="Fecha de Nacimiento" v-model="usuario.Fnacimiento" required>
                            <input type="text" class="form-control mb-2" placeholder="Nombre de Usuario" v-model="usuario.username" required maxlength="20" minlength="5">
                            <input type="password" class="form-control mb-2" placeholder="Contraseña" v-model="usuario.password" minlength="8">
                            
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Editar</button>
                                <button type="submit" class="btn btn-danger" data-dismiss="modal" @click="cancelarEdicion">Cancelar</button>
                            </div>
                        </form>
                            
                        <form @submit.prevent="agregar" v-else enctype="multipart/form-data">
                            <h3>Agregar Usuario</h3>

                            <input type="text" class="form-control mb-2" placeholder="Nombre" v-model="usuario.Nombre" required maxlength="30" minlength="3">                
                            <input type="text" class="form-control mb-2" placeholder="Apellido" v-model="usuario.Apellido" required maxlength="50" minlength="4">
                            <select class="form-control mb-2" v-model="usuario.Categoria" required>
                                <option value="" selected>Categorias</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Dueño">Dueño</option>
                                <option value="Empleado">Empleado</option>
                            </select>
                            <input type="text" class="form-control mb-2" placeholder="Telefono" v-model="usuario.Telefono" maxlength="9" minlength="2">
                            <input type="text" class="form-control mb-2" placeholder="DPI" v-model="usuario.DPI" maxlength="15" minlength="2">
                              
                            <input type="date" class="form-control mb-2" placeholder="Fecha de Nacimiento" v-model="usuario.Fnacimiento" required>
                            <input type="text" class="form-control mb-2" placeholder="Nombre de Usuario" v-model="usuario.username" required maxlength="20" minlength="5">
                            <input type="password" class="form-control mb-2" placeholder="Contraseña" v-model="usuario.password" required minlength="8">
                            
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
                        <th>Nombre</th>
                        <th>Categoria</th>
                        <th>Telefono</th>
                        <th>DPI</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in usuarios" :key="index">
                        <td class="table-secondary">{{ item.id}}</td>
                        
                        <td class="table-secondary">{{ item.Nombre }} {{ item.Apellido}}</td>
                        <td class="table-secondary">{{ item.Categoria}}</td>
                        <td class="table-secondary">{{ item.Telefono}}</td>
                        <td class="table-secondary">{{ item.DPI}}</td>
                        <td class="table-secondary">{{ item.Fnacimiento}}</td>
                        <td class="table-secondary">{{ item.username}}</td>
                        <td class="table-secondary">
                            <button class="btn btn-warning" v-if="item.Categoria != 'Administrador'" @click="editarFormulario(item)" data-toggle="modal"  data-target="#FormularioCrear">Editar</button>
                            <button class="btn btn-danger" v-if="item.Categoria != 'Administrador'" @click="eliminarusuario(item, index)">Eliminar</button>                            
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
      usuarios: [],
      modoEditar: false,
      usuario: {
          Nombre: '', 
          Apellido: '', 
          Categoria: '',
          Telefono: '',
          DPI: '',
          Fnacimiento: '',
          username: '',
          password: '',
          }
    }
  },
  created(){
    //Obtenemos los datos del controlador
    axios.get('/datosusuarios').then(res=>{
      this.usuarios = res.data;
    })
  },
  methods:{
    agregar(){      
      var params = new  FormData();
      params.append('Nombre', this.usuario.Nombre);
      params.append('Apellido', this.usuario.Apellido);
      params.append('Categoria', this.usuario.Categoria);
      params.append('Telefono', this.usuario.Telefono);
      params.append('DPI', this.usuario.DPI);
      params.append('Fnacimiento', this.usuario.Fnacimiento);
      params.append("username", this.usuario.username);
      params.append('password', this.usuario.password);      
      axios.post('/usuarios', params).then(res=>{
        const datosrecibidos = res.data;
        this.usuarios.push(datosrecibidos);
        this.cancelarEdicion();
        }).catch(error=>{
      alert(error)
    });
    },
    editarFormulario(item){
      this.modoEditar = true;
      this.usuario.id = item.id;
      this.usuario.Nombre = item.Nombre;
      this.usuario.Apellido = item.Apellido;
      this.usuario.Categoria = item.Categoria;
      this.usuario.Telefono = item.Telefono;
      this.usuario.DPI = item.DPI;
      this.usuario.Fnacimiento = item.Fnacimiento;
      this.usuario.username = item.username;
    },
    editarusuario(item){
      var edit = new  FormData();
      edit.append('Nombre', this.usuario.Nombre);
      edit.append('Apellido', this.usuario.Apellido);
      edit.append('Categoria', this.usuario.Categoria);
      edit.append('Telefono', this.usuario.Telefono);
      edit.append('DPI', this.usuario.DPI);
      edit.append('Fnacimiento', this.usuario.Fnacimiento);
      edit.append('username', this.usuario.username);
      edit.append('password', this.usuario.password);
      edit.append('_method','PUT');
      edit.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      axios.post(`/usuarios/${item.id}`, edit)
        .then(res=>{
          const index = this.usuarios.findIndex(item => item.id === res.data.id);
          this.usuarios[index] = res.data;
          this.cancelarEdicion();
        }).catch(error=>{
              alert(error)
            })
    },
    eliminarusuario(item, index){
      const confirmacion = confirm(`Eliminar Usuario ${item.Nombre}`);
      if(confirmacion){
        axios.delete(`/usuarios/${item.id}`)
          .then(()=>{
            this.usuarios.splice(index, 1);
          }).catch(error=>{
              alert(error)
            })
      }
    },
    cancelarEdicion(){
      this.modoEditar = false;
      this.usuario = {
          Nombre: '', 
          Apellido: '', 
          Categoria: '',
          Telefono: '',
          DPI: '',
          Fnacimiento: '',
          username: '',
          password: '',
          };
      $('#FormularioCrear').modal('hide');
    }
  }
}
</script>