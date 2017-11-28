@extends('adminlte::page')

@section('title', 'Perfil - Edita')

@section('content_header')
    <h1>Perfil <small> Cambio de Avatar</small></h1>
    @component('clientes/perfil/breadcrumbs')
        <li><i class="fa fa-pencil"></i> Edita avatar</li>
    @endcomponent
@stop

@section('adminlte_js')
    <link rel="stylesheet" type="text/css" href="/css/mix/forms.css">
    <script type="text/javascript" src="/js/mix/forms.js"></script>
    <script type="text/javascript" src="/vendor/adminlte/plugins/iCheck/icheck.min.js"></script>
    <style type="text/css">
        /* Limit image width to avoid overflow the container */
        img {
          max-width: 100%; /* This rule is very important, please do not ignore this! */
        }
    </style>
@stop
@section('content')
    @can('editar perfil clientes')
        <form enctype="multipart/form-data" id="perfil-edita-avatar" name="perfil-edita-avatar" method="post" action="{{ route('perfil.edita_avatar') }}">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-sm-6">
                    <div class="box box-primary">
                        <div class="box-body box-edit">
                            <div class="radio radio-success h2">
                                <input id="tipo_avatar_gravatar" name="tipo_avatar" type="radio" value="gravatar" @if(empty(Auth::user()->avatar)) checked @endif >
                                <label for="tipo_avatar_gravatar">Utilizar Gravatar</label>
                            </div>
                            <div class="text-center">
                                <img src="{{ Gravatar::src(Auth::user()->email) }}" class="profile-user-img img-responsive img-circle" alt="{{ Auth::user()->name }}">
                                <h4><span class="label label-default">{{ Auth::user()->email }}</span></h4>
                                <div class="callout callout-warning">
                                    <p>La disponibilidad de la imagen está sujeta a la conexión de internet y el funcionamiento del servicio de Gravatar</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="box box-primary">
                        <div class="box-body box-edit">
                            <div class="radio radio-success h2">
                                <input id="tipo_avatar_file" name="tipo_avatar" type="radio" value="file" @if(Auth::user()->avatar) checked @endif >
                                <label for="tipo_avatar_file">Subir imágen</label>
                            </div>
                            <div class="form-group">
                                <label id="avatar-btn" for="avatar" class="btn bg-purple"><i class="fa fa-upload"></i> Seleccionar imagen</label>
                                <input id="avatar" name="avatar" class="hidden" type="file" accept="image/jpeg, image/png" >
                                <input id="avatar_cropped" name="avatar_cropped" type="hidden">
                            </div>
                            <div id="cropper-image-container" class="text-center">
                                <img id="image" class="hidden">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-body table-responsive">
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                            <a href="{{ route('perfil.index') }}" role="button" class="btn btn-danger"><i class="fa fa-ban"></i> Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            $(document).ready(function() {
                //Evita tener la opcion de file sin el archivo
                $('form#perfil-edita-avatar').submit(function(e){
                    if($('#tipo_avatar_file').is(':checked')  && $('#avatar').val()== ''){
                        new PNotify({title: 'Imágen faltante', text: 'No has seleccionado una imagen.', type: 'error'});
                        e.preventDefault();
                        return false;
                    }
                });

                //Evita seleccionar un archivo y despues activar gravatar trayendo el archivo que se pensaba subir
                $('#tipo_avatar_gravatar').change(function(){
                    if($(this).is(':checked')){
                        $('#text-error').addClass('hidden');
                        $('#avatar_cropped').val('');
                        $('#avatar').val('');
                        $('#image').cropper('destroy');
                        $('#image').addClass('hidden');
                    }
                });

                //Evita salir d ela pagina son cambios
                $('form#perfil-edita').dirtyForms({dirtyClass: 'has-changes'});

                //Editor de imagen
                function CropperImage(img){
                    img.cropper('destroy');
                     //Cropper
                    img.cropper({
                        aspectRatio: 1 / 1,
                        movable: false,
                        zoomable: false,
                        rotatable: false,
                        scalable: false,
                        crop: function(e) {
                            // Setea dimensiones de la imagen al input hidden
                            $('#avatar_cropped').val('{"x": ' + e.x + ', "y": ' + e.y +', "width": ' + e.width + ', "height": ' + e.height + '}');
                        }
                    });
                }

                // Muestra la imagen que se selecciona por el input file
                $("#avatar").change(function () {
                    $('#text-error').addClass('hidden');
                    if ($("#avatar").val()) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#image').removeClass('hidden');
                            $("#image").attr('src', e.target.result);
                        };
                        reader.readAsDataURL(this.files[0]);
                        // Espera a que seleccione imagen y la cargue para despues aplicar el croppersss
                        setTimeout(function(){CropperImage($('#image'))}, 500);
                        $("#avatar-btn").html('<i class="fa fa-upload"></i> Seleccionar otra imagen');
                    } else {
                        $("#avatar-btn").html('<i class="fa fa-upload"></i> Seleccionar imagen');
                    }
                });

                // Elimina src al apretar el botón para evitar bug
                $("#avatar-btn").click(function () {
                    $('#tipo_avatar_file').prop("checked",true);
                    $("#cropper-image-container").html('<img id="image" class="hidden">');

                });
            });
        </script>
    @else
        <div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-ban"></i> Restringido</h4>
            No cuenta con los permisos necesarios para poder ver este recurso.
        </div>
    @endcan
@stop
