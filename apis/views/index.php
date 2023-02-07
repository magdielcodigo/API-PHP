<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.2/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap5.min.css">
</head>
<body>
    
    <div class="container">
        <div class="mt-5 card">
            <div class="card-body">
                <table id="table_id" class="table w-100">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>nombre</th>
                            <th>apellido</th>
                            <th>edad</th>
                            <th>email</th>
                            <th>opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <button class="btn btn-dark position-fixed bottom-0 end-0 m-4" id="add">Agregar</button>

    <!-- Inicializacion de seguridad csrf -->
    <?php set_csrf(); ?>

    <!-- Jquery, bootstrap y datatables -->
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        var table = null

        class apiCrud{
            constructor(){
                this.initTable()
            }
            initTable(){
                if(table){
                    table.ajax.reload()
                }else{
                    table = $("#table_id").DataTable({
                        ajax:{
                            headers: {"X-AUTH-TOKEN" : 'asfdhajhbsjkdfagkeyuiwh873wa7823wgu'},
                            url:'<?php echo rp;?>read',
                            method: 'POST',
                            dataSrc: 'data',
                            data: {
                                csrf: '<?php echo $_SESSION["csrf"]; ?>'
                            }
                        },
                        columns:[
                            {data: 'id'},
                            {data: 'name'},
                            {data: 'last'},
                            {data: 'years'},
                            {data: 'email'},
                            {
                                data:null,
                                render:function(data,type,row){
                                    return `<button class="btn btn-danger btnDelete" data-id="${row.id}">Eliminar</button>
                                            <button class="btn btn-primary btnEdit" 
                                                    data-id="${row.id}"
                                                    data-name="${row.name}"
                                                    data-last="${row.last}"
                                                    data-years="${row.years}"
                                                    data-email="${row.email}"
                                                    data-pass="${row.pass}"
                                                    >Editar</button>`
                                }
                            }
                        ]
                    })
                }
            }
        }
        $(() => {
            new apiCrud();            
            
            $(document).on('click','.btnDelete',function(){
                const idRecord = $(this).data('id')
                Swal.fire({
                    title:'¿Desea eliminar el dato?',
                    icon:'info',
                    showDenyButton:true,
                    confirmButtonText:'Eliminar'
                }).then((params)=>{
                    if(params.isConfirmed){
                        $.ajax({
                            headers: {"X-AUTH-TOKEN" : 'asfdhajhbsjkdfagkeyuiwh873wa7823wgu'},
                            url:'<?php echo rp;?>delete',
                            method:'POST',
                            data:{
                                csrf: '<?php echo $_SESSION["csrf"]; ?>',
                                id: idRecord
                            },
                            success:function(response){
                                const msg = JSON.parse(response)
                                Swal.fire(msg)
                                table.ajax.reload()
                            }
                        });
                    }
                })
            })

            $("#add").click(async ()=>{
                const { value, isConfirmed } = await Swal.fire({
                    title:'Agregar usuario',
                    html:`
                        <div class="container-fluid">
                            <input type="text" class="form-control mb-2" id="name" placeholder="Name"/>
                            <input type="text" class="form-control mb-2" id="last" placeholder="Last Name"/>
                            <input type="text" class="form-control mb-2" id="year" placeholder="Years old"/>
                            <input type="text" class="form-control mb-2" id="email" placeholder="Email"/>
                            <input type="text" class="form-control mb-2" id="pass" placeholder="Password"/>
                        </div>
                    `,
                    focusConfirm: false,
                    preConfirm: ()=>{
                        const list = ['name','last','year','email','pass']
                        let data = {}
                        for(let l in list){
                            data[list[l]] = $(`#${list[l]}`).val()
                            if(!$(`#${list[l]}`).val()){
                                Swal.fire('Favor de llenar todos los campos','','error')
                                return
                            }
                        }
                        return data
                    },
                    confirmButtonText: 'Guardar',
                    showDenyButton: true,
                    denyButtonText: 'Cancelar'
                })
                if(isConfirmed){
                    $.ajax({
                        headers: {"X-AUTH-TOKEN" : 'asfdhajhbsjkdfagkeyuiwh873wa7823wgu'},
                        url:'<?php echo rp;?>create',
                        method:'POST',
                        data:{
                            csrf:'<?php echo $_SESSION['csrf'];?>',
                            data: value
                        },
                        success:function(response){
                            const { title, icon } = JSON.parse(response);
                            if(icon == 'success')
                                table.ajax.reload()
                            Swal.fire({
                                title: title,
                                icon: icon
                            })
                        }
                    })
                }
            })

            $(document).on('click','.btnEdit',async function(){
                const record = {
                    id:$(this).data('id'),
                    name:$(this).data('name'),
                    last:$(this).data('last'),
                    year:$(this).data('years'),
                    email:$(this).data('email'),
                    pass:$(this).data('pass')
                }
                
                const { value, isConfirmed } = await Swal.fire({
                    title:'Editar usuario',
                    html:`
                        <div class="container-fluid">
                            <input type="hidden" class="form-control mb-2" id="id" value="${record['id']}"/>
                            <input type="text" class="form-control mb-2" id="name" placeholder="Name" value="${record['name']}"/>
                            <input type="text" class="form-control mb-2" id="last" placeholder="Last Name" value="${record['last']}"/>
                            <input type="text" class="form-control mb-2" id="year" placeholder="Years old" value="${record['year']}"/>
                            <input type="text" class="form-control mb-2" id="email" placeholder="Email" value="${record['email']}"/>
                            <input type="text" class="form-control mb-2" id="pass" placeholder="Password" value="${record['pass']}"/>
                        </div>
                    `,
                    focusConfirm: false,
                    preConfirm: ()=>{
                        const list = ['id','name','last','year','email','pass']
                        let data = {}
                        for(let l in list){
                            data[list[l]] = $(`#${list[l]}`).val()
                            if(!$(`#${list[l]}`).val()){
                                Swal.fire('Favor de llenar todos los campos','','error')
                                return
                            }
                        }
                        return data
                    },
                    confirmButtonText: 'Guardar',
                    showDenyButton: true,
                    denyButtonText: 'Cancelar'
                })
                if(isConfirmed){
                    $.ajax({
                        headers: {"X-AUTH-TOKEN" : 'asfdhajhbsjkdfagkeyuiwh873wa7823wgu'},
                        url:'<?php echo rp;?>update',
                        method:'POST',
                        data:{
                            csrf:'<?php echo $_SESSION['csrf'];?>',
                            data: value
                        },
                        success:function(response){
                            const { title, icon } = JSON.parse(response);
                            if(icon == 'success')
                                table.ajax.reload()
                            Swal.fire({
                                title: title,
                                icon: icon
                            })
                        }
                    })
                }
            })

        });
    </script>
</body>
</html>