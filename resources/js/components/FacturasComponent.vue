<template>
  <div><br><br>
      <div class="container-fluid">

        <a href="/detalles">
            <button class="btn btn-success" type="button">Registrar ventas</button>
        </a>
        <button class="btn btn-info" @click="Antiguas()" type="button">Mas Antiguas</button>
        <button class="btn btn-info" @click="Recientes()" type="button">Mas Recientes</button>
        <button class="btn btn-info" @click="Hoy()" type="button">De Hoy</button>
        <button class="btn btn-info" @click="Todas()" type="button">Todas</button>

          <div class="row mt-4">

            <div class="col-1"></div>
            <div class="col-10">

                <div v-for="(item, index) in ventas" :key="index" class="card bg-secondary text-white mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Factura No.{{ item.id }}</h5>
                        <p class="card-text m-auto">Vendedor: {{item.Vnombre}} {{item.Vapellido}}</p>
                        <p class="card-text m-auto">Fecha y Hora: {{item.Fecha}}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text m-auto">Nombre: {{item.Nombre}}</p>
                        <p class="card-text m-auto">Direccion: {{item.Direccion}}</p>
                        <p class="card-text m-auto">NIT: {{item.NIT}}</p>
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-bordered table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center" scope="col">Cant.</th>
                                        <th class="text-center" scope="col">Descripcion</th>
                                        <th class="text-center" scope="col">Precio</th>
                                        <th class="text-center" scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody v-on="DetalleE(item.id)">
                                    <tr v-for="(elemento, index) in deta" :key="index">
                                        <td class="text-center table-secondary">{{ elemento.Cantidad }}
                                        <td class="table-secondary">{{ elemento.Descripcion }}</td>
                                        <td class="text-center table-secondary">Q.{{ elemento.Precio }}</td>
                                        <td class="text-center table-secondary">Q.{{ elemento.Total }}</td>
                                    </tr>
                                    <tr>
                                        <td class="table-dark text-right" colspan="3">SubTotal</td>
                                        <td class="table-secondary text-center">Q.{{ item.SubTotal }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p class="card-subtitle">Total: Q.{{ item.Total }}</p>
                        <p class="card-subtitle">Pago: Q. {{item.Pago}} Cambio: Q.{{item.Cambio}}</p>
                    </div>
                </div>                

            </div>
            <div class="col-1"></div>
            
          </div>
        </div>
  </div>
</template>

<script>
export default {
    data() {
    return {
      //Intanciamos los arreglos y variables que vallamos a utilizar
      ventas:[],
      detalles:[],
      deta:[],
    }    
  },
  created(){
    //Obtenemos los datos del controlador
    axios.get('/datosventas').then(res=>{
      this.ventas = res.data;
      //Le damos la vuelta al arreglo para mostrar las ventas mas recientes
      this.ventas.reverse();
    }).catch(error=>{
        alert(error)
      });

    axios.get('/datosdetalles').then(res=>{
      this.detalles = res.data;
    }).catch(error=>{
      alert(error)
    });

  },
  methods:{
      DetalleE(id){
        let temp = this.detalles;
        let deta = [];
        temp.forEach(function(elemento, indice, array) {
            if( temp[indice]["Factura"] == id){
                deta.push(temp[indice]);
            }
        });
        this.deta = deta;
      },

      Antiguas(){
        this.ventas.sort((a,b) => new Date(a.Fecha) - new Date(b.Fecha));
      },

      Recientes(){
        this.ventas.sort((a,b) => new Date(b.Fecha) - new Date(a.Fecha));
      },

      Hoy(){
        let temp = this.ventas;
        let deta = [];
        const fecha = new Date();fecha.setHours(0,0,0);        
        temp.forEach(function(elemento, indice, array) {
            if( new Date(temp[indice]["Fecha"]) > fecha){
                deta.push(temp[indice]);
            }
        });
        this.ventas = deta;
      },

      Todas(){
        axios.get('/datosventas').then(res=>{
          this.ventas = res.data;
          this.ventas.reverse();
        }).catch(error=>{
            alert(error)
          });
      },
  },
}
</script>