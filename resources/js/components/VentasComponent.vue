<template>
    <div><br><br>
        <div class="container-fluid">
          <div class="row">
            <div class="col bg-info" name="Formulario">

              <form @submit.prevent="Agregar" enctype="multipart/form-data">
                <br>
                <h3 class="text-center">Registro de Venta</h3>
                <div>
                  <p>Fecha y Hora:{{" " + hoy.getDate() + '/' + (hoy.getMonth()+1)+'/'+hoy.getFullYear()+"     "+hoy.getHours()+':'+hoy.getMinutes()}}</p>
                </div>

                <input type="text" class="form-control mb-2" placeholder="Nombre" v-model="venta.Nombre" maxlength="50">
                <input type="text" class="form-control mb-2" placeholder="Direccion" v-model="venta.Direccion" maxlength="50">
                <input type="text" class="form-control mb-2" placeholder="Nit" v-model="venta.Nit" maxlength="15">

                <div class="table-responsive-sm">
                  <table class="table table-striped table-bordered table-sm">
                    <thead class="thead-dark">
                      <tr>
                        <th class="text-center" colspan="2" scope="col">Cant.</th>
                        <th class="text-center" scope="col">Descripcion</th>
                        <th class="text-center" scope="col">Precio</th>
                        <th class="text-center" colspan="2" scope="col">Total</th>
                      </tr> 
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in detalles" :key="index">
                        <td class="text-center table-secondary" style="width:50px">
                          <button type="button" class="btn btn-sm" @click="Restar(index)">
                            <img src="storage/uploads/less.png" alt="Disminuir" width="20px" height="20px">
                          </button>
                        </td>
                        <td class="text-center table-secondary">{{ item.Cantidad }}
                        <td class="table-secondary">{{ item.Descripcion }}</td>
                        <td class="text-center table-secondary">Q.{{ item.Precio }}</td>
                        <td class="text-center table-secondary">Q.{{ item.Total }}</td>
                        <td class="text-ceter table-secondary" style="width:50px">
                          <button type="button" class="btn btn-sm">
                            <img src="storage/uploads/remove.png" alt="Disminuir" width="20px" height="20px" @click="Remover(index)">
                          </button>                          
                        </td>
                      </tr>
                      <tr>
                        <th class="text-right table-dark" colspan="4">SubTotal</th>
                        <td colspan="2" class="table-secondary text-center">
                          Q.{{ Stotal }}
                        </td>
                      </tr>
                      <tr>
                        <th class="text-right table-dark" colspan="4">Total</th>
                        <td colspan="2" class="table-secondary text-center">
                          Q.{{ Stotal }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <button type="submit" class="btn btn-success">Agregar</button>
                <button type="button" class="btn btn-danger" @click="Limpiar()">Cancelar</button>
              </form><br>

            </div>
            <div class="col">
              <div class="d-flex flex-row justify-content-center alig-items-center">
                <button @click="ModoComida=true" type="button" class="btn btn-secondary btn-lg mr-5">Comidas</button>
                <button @click="ModoComida=false" type="button" class="btn btn-secondary btn-lg">Bebidas</button>
              </div><br>

              <div v-if="ModoComida" name="Comidas">
                <button @click="AgregarDetalle(item)" type="button" class="btn btn-secondary btn-lg mr-2 Cuadrado" v-for="(item, index) in comidas" :key="index">
                  <img :src="'storage/' + item.Foto" alt="FotoComida" width="100" height="100"><br>
                  {{item.Nombre}}
                </button>
              </div>
              <div v-else name="Bebidas">
                <button type="button" @click="AgregarDetalle(item)" class="btn btn-secondary btn-lg mr-2 Cuadrado" v-for="(item, index) in bebidas" :key="index">
                  <img :src="'storage/' + item.Foto" alt="FotoComida" width="100" height="100"><br>
                  {{item.Nombre}}
                </button>
              </div>
            </div>
          </div>
        </div> 

    </div>
</template>

<script>
export default {
    data() {
    return {
      //Intanciamos los arreglos y variables que vallamos a utilizar
      ModoComida: true,
      comidas: [],
      bebidas: [],
      detalles: [],
      venta: {
        id: '',
        Nombre: '', 
        Direccion: '', 
        Nit: '',
        SubTotal: '',
        Total: '',
        Pago: '',
        Cambio: '',
      },
      detalle: {
        Factura: '',
        Cantidad: 0,
        Descripcion: '',
        Precio: 0,
        Total: 0,
      },
      hoy: new Date(),
    }    
  },
  created(){
    //Obtenemos los datos del controlador
    axios.get('/datoscomidas').then(res=>{
      this.comidas = res.data;
    }).catch(error=>{
        alert(error)
      });

    axios.get('/datosbebidas').then(res=>{
      this.bebidas = res.data;
    }).catch(error=>{
      alert(error)
    });

  },
  methods:{
    Agregar(){      

      let pago = prompt("El total a cobrar es Q." + this.venta.Total + ", ingrese el pago:");
      
      if(pago >= parseInt(this.venta.Total) ){ 
        var c = pago - this.venta.Total;       
        let cambio = confirm('El cambio a entregar es : Q.' + c)
        if(cambio){

          this.venta.Pago = pago.toString();
          this.venta.Cambio = c.toString();

          var params = new  FormData();
          params.append('Nombre', this.venta.Nombre);
          params.append('Direccion', this.venta.Direccion);
          params.append('Nit', this.venta.Nit);
          params.append('SubTotal', this.venta.SubTotal);
          params.append('Total', this.venta.Total);
          params.append('Pago', this.venta.Pago);
          params.append('Cambio', this.venta.Cambio);

          axios.post('/ventas', params)
          .then((res) =>{
            this.venta.id = res.data.id;
            this.SubirDetalle(res.data.id);
            this.Limpiar();
          }).catch(
            error=>{alert(error)
            })
        }
      }else{
        alert('El pago debe ser mayor a monto de la venta');
      }      
    },
    AgregarDetalle(item){
      const index = this.detalles.findIndex(datos => datos.Descripcion === item.Nombre);
      if(index != -1){
        this.detalle = this.detalles[index];

        this.detalle.Cantidad = this.detalle.Cantidad+1;
        this.detalle.Total = this.detalle.Cantidad * this.detalle.Precio;

        this.detalles[index] = this.detalle;
      }else{
        this.detalle.Cantidad = this.detalle.Cantidad+1;
        this.detalle.Descripcion = item.Nombre;
        this.detalle.Precio = parseFloat(item.Precio);
        this.detalle.Total = this.detalle.Cantidad * this.detalle.Precio;

        this.detalles.push(this.detalle);
      }

      this.LimpiarDetalle();
    },
    Restar(index){
        this.detalle = this.detalles[index];

        if(this.detalle.Cantidad > 1){
          this.detalle.Cantidad = this.detalle.Cantidad-1;
          this.detalle.Total = this.detalle.Cantidad * this.detalle.Precio;
        }else{
          this.Remover(index);
        }
        

        this.LimpiarDetalle();
    },
    Remover(index){
        this.detalles.splice(index, 1);
    },
    SubirDetalle(id){
      let deta = this.detalles;
      deta.forEach(function(elemento, indice, array) {
        deta[indice]["Factura"] = id;
        axios.post('/detalles', deta[indice]).catch(error=>{alert(error)});
      });
    },
    Limpiar(){
      this.venta = {
          Nombre: '', 
          Direccion: '', 
          Nit: '',
          SubTotal: 0,
          Total: '',
          Pago: '',
          Cambio: '',
          };
      this.detalles = [];
    },
    LimpiarDetalle(){
      this.detalle = {
        Cantidad: 0,
        Descripcion: '',
        Precio: 0,
        Total: 0,
      }
    },
  },
  computed:{
    Stotal: function () {
    let sub= 0;
    this.detalles.forEach(function(elemento, indice, array) {
      sub += elemento.Total;
    });
    this.venta.SubTotal = sub.toString();
    this.venta.Total = sub.toString();
    return sub;
    }
  }
}
</script>