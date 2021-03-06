<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ingresos
                        <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!-- Inicio Listado-->
                    <template v-if="listado==1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Numero Comprobante</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Usuario</th>
                                            <th>Proveedor</th>
                                            <th>Tipo Comprobante</th>
                                            <th>Serie Comprobante</th>
                                            <th>Numero Comprobante</th>
                                            <th>Fecha Hora</th>
                                            <th>Total</th>
                                            <th>Impuesto</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                            <td>
                                                <button type="button" @click="verIngreso(ingreso.id)" class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                                </button> &nbsp;
                                                <template v-if="ingreso.estado=='Registrado'">
                                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)">
                                                        <i class="icon-trash"></i>
                                                    </button> 
                                                </template>
                                                
                                            </td>
                                        <td v-text="ingreso.usuario"></td>
                                            <td v-text="ingreso.nombre"></td>
                                            <td v-text="ingreso.tipo_comprobante"></td>
                                            <td v-text="ingreso.serie_comprobante"></td>
                                            <td v-text="ingreso.num_comprobante"></td>
                                            <td v-text="ingreso.fecha_hora"></td>
                                            <td v-text="ingreso.total"></td>
                                            <td v-text="ingreso.impuesto"></td>
                                            <td v-text="ingreso.estado"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                    <!-- Fin LIstado-->
                    <!-- Inicio Detalle-->
                    <template v-else-if="listado==0">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Proveedor(*)</label>
                                        <v-select
                                        :on-search="selectProveedor"
                                        label="nombre"
                                        :options="arrayProveedor"
                                        placeholder="Buscar Proveedores..."
                                        :onChange="getDatosProveedor"                                        
                                    >

                                    </v-select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Impuesto(*)</label>
                                    <input type="text" class="form-control" v-model="impuesto"> 
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Tipo Comprobante(*)</label>
                                        <select class="form-control" v-model="tipo_comprobante">
                                        <option value="0">Seleccionar</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="TICKET">Ticket</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Serie Comprobante</label>
                                        <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Numero Comprobante(*)</label>
                                        <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div v-show="errorIngreso" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error"></div>
                                    </div>

                                </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="col-md-6">
                                    <label>Art??culo <span style="color:red;" v-show="idarticulo==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Ingrese Articulo">
                                        <button @click="abrirModal()" class="btn btn-primary"><span class="fa fa-plus"></span>...</button>
                                        <input type="text" readonly class="form-control" v-model="articulo">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Precio <span style="color:red;" v-show="precio==0">(*Precio)</span></label>
                                        <input type="number" value="0" step="any" class="form-control" v-model="precio">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Cantidad <span style="color:red;" v-show="cantidad==0">(*Cantidad)</span></label>
                                        <input type="number" value="0" step="any" class="form-control" v-model="cantidad">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Art??culo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                            <tbody v-if="arrayDetalle.length">
                                                <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                                    <td>
                                                        <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-close"></i></button>
                                                    </td>
                                                    <td v-text="detalle.articulo">
                                                        
                                                    </td>
                                                    <td>
                                                        <input   v-model="detalle.precio"  type="number" value="3" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input v-model="detalle.cantidad"  type="number" value="2" class="form-control">
                                                    </td>
                                                    <td>
                                                        {{detalle.precio*detalle.cantidad}}
                                                    </td>
                                                </tr>
                                                <tr style="background-color: #CEECFS;">
                                                    <td colspan="4" align="right"><strong> Total Parcial:</strong></td>
                                                    <td>$ {{ totalParcial=(total-totalImpuesto).toFixed(2) }}</td>
                                                </tr>
                                                <tr style="background-color: #CEECFS;">
                                                    <td colspan="4" align="right"><strong> Total Impuesto:</strong></td>
                                                    <td>$ {{ totalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                                </tr>
                                                <tr style="background-color: #CEECFS;">
                                                    <td colspan="4" align="right"><strong> Total Neto:</strong></td>
                                                    <td>$ {{ total=calcularTotal }}</td>
                                                </tr>  

                                            </tbody>
                                            <tbody v-else>
                                                <tr>
                                                    <td colspan="5">
                                                        NO hAy Nada
                                                    </td>
                                                </tr>

                                            </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                    <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- Fin  Detalle-->

                    <!-- VER INGRESO-->
                    <template v-else-if="listado==2">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Proveedor(*)</label>
                                     <p v-text="proveedor"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Impuesto(*)</label>
                                    <p v-text="impuesto"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Tipo Comprobante(*)</label>
                                        <p v-text="tipo_comprobante"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Serie Comprobante</label>
                                        <p v-text="serie_comprobante"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Numero Comprobante(*)</label>
                                        <p v-text="num_comprobante"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Art??culo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                            <tbody v-if="arrayDetalle.length">
                                                <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                                    <td v-text="detalle.articulo">
                                                        
                                                    </td>
                                                    <td v-text="detalle.precio">
                                                        
                                                    </td>
                                                    <td v-text="detalle.cantidad">
                                                        
                                                    </td>
                                                    <td>
                                                        {{detalle.precio*detalle.cantidad}}
                                                    </td>
                                                </tr>
                                                <tr style="background-color: #CEECFS;">
                                                    <td colspan="4" align="right"><strong> Total Parcial:</strong></td>
                                                    <td>$ {{ totalParcial=(total-totalImpuesto).toFixed(2) }}</td>
                                                </tr>
                                                <tr style="background-color: #CEECFS;">
                                                    <td colspan="4" align="right"><strong> Total Impuesto:</strong></td>
                                                    <td>$ {{ totalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                                </tr>
                                                <tr style="background-color: #CEECFS;">
                                                    <td colspan="4" align="right"><strong> Total Neto:</strong></td>
                                                    <td>$ {{ total=calcularTotal }}</td>
                                                </tr>  

                                            </tbody>
                                            <tbody v-else>
                                                <tr>
                                                    <td colspan="5">
                                                        NO hAy Nada
                                                    </td>
                                                </tr>

                                            </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                    
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- FIN VER INGRESO-->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">X</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterioA">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripci??n</option>
                                        <option value="CODIGO">Codigo</option>
                                        </select>
                                        <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarArticulo(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                        </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Categoria</th>
                                            <th>Precio Venta</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                            <td>
                                                <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="articulo.codigo"></td>
                                            <td v-text="articulo.nombre"></td>
                                            <td v-text="articulo.nombre_categoria"></td>
                                            <td v-text="articulo.precio_venta"></td>
                                            <td v-text="articulo.stock"></td>
                                            <td>
                                                <div v-if="articulo.condicion">
                                                <span class="badge badge-success">Activo</span>
                                                </div>

                                                <div v-else>
                                                <span class="badge badge-danger">Inactivo</span>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()" >Guardar</button>
                            <button type="button" v-if="tipoAccion==2" @click="actualizarPersona()" class="btn btn-success">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            
        </main>
</template>

<script>
    
    import vSelect from 'vue-select';
    export default {
        data (){
            return{
                ingreso_id : 0,
                idproveedor : 0,
                proveedor : '',
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                num_comprobante : '',
                impuesto : 0.10,
                total : 0.0,
                totalImpuesto : 0.0,
                totalParcial : 0.0,
                arrayIngreso : [],
                arrayDetalle : [],
                arrayProveedor : [],
                listado: 1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorIngreso : 0,
                errorMostrarMsjIngreso : [],
                //Pagination
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'num_comprobante',
                buscar : '',
                buscarA:'',
                criterioA: 'nombre',
                arrayArticulo : [],
                idarticulo:0,
                codigo: '',
                articulo: '',
                precio: 0,
                cantidad: 0
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginaci??n
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  
                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             
            },
            calcularTotal: function () {
               // var resultado = 0.0;
                var resultado=0.0;
                for (var i = 0; i <this.arrayDetalle.length; i++) {
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad)
                    
                }
                return resultado;
            }
        },
        methods : {
            listarIngreso(page, buscar, criterio){
                let me=this;
                var url= '/ingreso?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    // handle success
                    me.arrayIngreso = respuesta.ingresos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            selectProveedor(search,loading){
                let me=this;
                loading(true)
                var url= '/proveedor/selectProveedor?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayProveedor=respuesta.proveedores;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosProveedor(val1){
                let me = this;
                me.loading = true;
                me.idproveedor = val1.id;
            },
            buscarArticulo(){
            //est
                let me=this;
                var url= '/articulo/buscarArticulo?filtro=' + me.codigo;
                
                 axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticulo = respuesta.articulos;
                    if (me.arrayArticulo.length>0){
                        me.articulo=me.arrayArticulo[0]['nombre'];
                        me.idarticulo=me.arrayArticulo[0]['id'];
                    }
                    else{
                        me.articulo='No existe art??culo';
                        me.idarticulo=0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },  
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la peticion para visualizar la data de ese Pagina
                me.listarIngreso(page, buscar, criterio);
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].idarticulo==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            agregarDetalle(){
                let me=this;
                if(me.idarticulo==0 || me.cantidad==0 || me.precio==0){
                }
                else{
                    if(me.encuentra(me.idarticulo)){
                        Swal.fire({
                            icon: 'error',
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese art??culo ya se encuentra agregado!',
                            })
                    }
                    else{
                       me.arrayDetalle.push({
                            idarticulo: me.idarticulo,
                            articulo: me.articulo,
                            cantidad: me.cantidad,
                            precio: me.precio
                        });
                        me.codigo="";
                        me.idarticulo=0;
                        me.articulo="";
                        me.cantidad=0;
                        me.precio=0; 
                    }
                    
                }
                
            },
            agregarDetalleModal(data=[]){
                let me=this;
                 if(me.encuentra(data['id'])){
                        Swal.fire({
                            icon: 'error',
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese art??culo ya se encuentra agregado!',
                            })
                    }
                    else{
                       me.arrayDetalle.push({
                            idarticulo: data['id'],
                            articulo: data['nombre'],
                            cantidad: 1,
                            precio: 1
                        });
                         
                    }
                    
            },
            listarArticulo(buscar, criterio){
                let me=this;
               
                var url= '/articulo/listarArticulo?buscar='+ buscar + '&criterio='+ criterio;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    // handle success
                    me.arrayArticulo = respuesta.articulos.data;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            registrarIngreso(){
                if (this.validarIngreso()) {
                    return;
                }
                let me = this;
                axios.post('/ingreso/registrar', {
                    'idproveedor': this.idproveedor,
                    'tipo_comprobante': this.tipo_comprobante,
                    'serie_comprobante': this.serie_comprobante,
                    'num_comprobante': this.num_comprobante,
                    'impuesto': this.impuesto,
                    'total': this.total,
                    'data': this.arrayDetalle,
                }).then(function (response) {
                    me.listado=1;
                    me.listarIngreso(1, '' , 'num_comprobante');
                    me.idproveedor=0;
                    me.tipo_comprobante='BOLETA';
                    me.serie_comprobante='';
                    me.num_comprobante='';
                    me.impuesto=0.10;
                    me.total=0.0;
                    me.idarticulo=0;
                    me.articulo='';
                    me.cantidad=0;
                    me.precio=0;
                    me.arrayDetalle=[];
                }).catch(function (error) {
                    console.log(error);
                    
                })
            },
            actualizarPersona(){
                 if (this.validarPersona()) {
                    return;
                }
                let me = this;
                axios.put('/user/actualizar', {
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento': this.num_documento,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'email': this.email,
                    'usuario': this.usuario,
                    'password': this.password,
                    'idrol': this.idrol,
                    'id' : this.persona_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarPersona(1, '' , 'nombre');
                }).catch(function (error) {
                    console.log(error);
                    
                })
            },
             cerrarModal(){
                this.modal = 0;
                this.tituloModal ='';
                
            },
            abrirModal(){
                this.arrayArticulo=[];
                this.modal = 1;
                this.tituloModal = 'Seleccione Articulo';
            },
            desactivarIngreso(id){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
        title: 'Esta seguro?',
        text: "de Eliminar Este Ingreso!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Aceptar!',
        cancelButtonText: 'No, Cancelar!',
        reverseButtons: true
        }).then((result) => {
        if (result.isConfirmed) {

             let me = this;
    axios.put('/ingreso/desactivar', {
        'id': id
    }).then(function (response) {
        me.listarIngreso(1, '' , 'num_comprobante');
        swalWithBootstrapButtons.fire(
            'Anulado!',
            'El Ingreso se Anulo con Exito  .',
            'danger'
                )
        }).catch(function (error) {
        console.log(error); 
        
    })

            
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            // swalWithBootstrapButtons.fire(
            // 'Cancelled',
            // 'Your imaginary file is safe :)',
            // 'error'
            // )
        }
        })
},
            validarIngreso(){
                this.errorIngreso=0;
                this.errorMostrarMsjIngreso = [];
                if (this.idproveedor==0)this.errorMostrarMsjIngreso.push("Seleccione Proveedor.");
                if (this.tipo_comprobante==0)this.errorMostrarMsjIngreso.push("Seleccione Comprobante.");
                if (!this.num_comprobante)this.errorMostrarMsjIngreso.push("Ingrese  Numero Comprobante.");
                if (!this.impuesto)this.errorMostrarMsjIngreso.push("Ingrese  Impuesto de Compra.");
                if (this.arrayDetalle.length<=0)this.errorMostrarMsjIngreso.push("Ingrese Detalle.");
                if(this.errorMostrarMsjIngreso.length)this.errorIngreso = 1;
                return this.errorIngreso;
                
            },
            mostrarDetalle(){
                let me = this;
                this.listado=0;
                me.idproveedor=0;
                    me.tipo_comprobante='BOLETA';
                    me.serie_comprobante='';
                    me.num_comprobante='';
                    me.impuesto=0.10;
                    me.total=0.0;
                    me.idarticulo=0;
                    me.articulo='';
                    me.cantidad=0;
                    me.precio=0;
                    me.arrayDetalle=[];
            },
            ocultarDetalle(){
                this.listado=1;
                
            },
            verIngreso(id){
                let me = this;
               me.listado=2;

               //Obtener los Datos del Ingreso
            var arrayIngresoT=[];
            var url= '/ingreso/obtenerCabecera?id=' + id;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    // handle success
                    arrayIngresoT = respuesta.ingreso;
                
                    me.proveedor = arrayIngresoT[0]['nombre'];
                    me.tipo_comprobante = arrayIngresoT[0]['tipo_comprobante'];
                    me.serie_comprobante = arrayIngresoT[0]['serie_comprobante'];
                    me.num_comprobante = arrayIngresoT[0]['num_comprobante'];
                    me.impuesto = arrayIngresoT[0]['impuesto'];
                    me.total = arrayIngresoT[0]['total'];
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });

               //Obtener los Datos de los Detalles
               var urld= '/ingreso/obtenerDetalles?id=' + id;
                
                axios.get(urld).then(function (response) {
                    var respuesta = response.data;
                    // handle success
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });

            }
           
        },
        mounted() {
            this.listarIngreso(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
     @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }
</style>