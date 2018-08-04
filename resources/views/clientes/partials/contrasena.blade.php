<!-- Partial: contrasena -- [ -->
    <input type="hidden" name="change-password" value="1">
        La nueva contraseña debe contener al menos 9 caracteres y mínimo tres caracteres entre los siguientes grupos: caracteres en minúsculas, caracteres en maypusculas, dígitos, y no alfanuméricos.
        <br>&nbsp;
    <div class="form-group has-feedback">
        <label for="password">Nueva contraseña</label>
        <input id="password" name="password" class="form-control" type="password" value="" placeholder="Contraseña" minlength="6" maxlength="255" data-toggle="password" data-placement="before" data-message="Mostrar contraseña" required>
        <span class="fa fa-asterisk form-control-feedback" aria-hidden="true"></span>
        <span id="password_error" class="help-block hidden">Error</span>
    </div>
    <div class="form-group has-feedback">
        <label class="control-label" for="inputError2">Validación de contraseña</label>
        <input id="password_confirmation" name="password_confirmation" class="form-control" type="password" value="" placeholder="Validación de contraseña" minlength="6" maxlength="255" data-toggle="password" data-placement="before" data-message="Mostrar contraseña" required>
        <span class="fa fa-asterisk form-control-feedback" aria-hidden="true"></span>
        <span id="password_confirmation_error" class="help-block hidden">Error</span>
    </div>
    <div class="form-group">
        <input id="generate-password" name="generate-password" type="button" class="btn btn-sm btn-default" value="Generar contraseña">
        <input id="copy-password" name="copy-password" type="button" class="btn btn-sm btn-default hidden" value="Copiar contraseña">
    </div>
    <script>
        function CheckPasswords() {
            // Valida que las contraseñas sean iguales
            if($('#password_confirmation').val() !== $('#password').val()){
                // Muestra error de passwords diferentes
                $('#password').closest('div.form-group').addClass('has-error');
                $('#password_confirmation').closest('div.form-group').addClass('has-error');
                $('#password_error').html('Las contraseñas no coinciden').removeClass('hidden').fadeIn();
                $('#password_confirmation_error').html('Las contraseñas no coinciden').removeClass('hidden').fadeIn();
                $('#password').focus();
                return false
            }
            // Valida caracteres
            if($('#password').val().length < $('#password').attr('minlength')) {
                // Muestra error
                $('#password').closest('div.form-group').addClass('has-error');
                $('#password_error').html('La contraseña debe tener al menos ' + $('#password').attr('minlength') + ' caracteres.').removeClass('hidden').fadeIn();
                $('#password').focus();
                return false
            }
            if($('#password').val().length > $('#password').attr('maxlength')) {
                // Muestra error
                $('#password').closest('div.form-group').addClass('has-error');
                $('#password_error').html('La contraseña debe ser menor a ' + $('#password').attr('maxlength') + ' caracteres.').removeClass('hidden').fadeIn();
                $('#password').focus();
                return false
            }
            // Fin de validaciones
            ResetPasswordErrors();
            return true;
        }
        function ResetPasswordErrors() {
            $('#password').closest('div.form-group').removeClass('has-error');
            $('#password_confirmation').closest('div.form-group').removeClass('has-error');
            $('#password_error').addClass('hidden');
            $('#password_confirmation_error').addClass('hidden');
        }
        $(document).ready(function() {
            // Cambio en passwords
            $('#password, #password_confirmation').change(function() {
                $('#copy-password').addClass('hidden');
                ResetPasswordErrors();
            });
            // Generar password
            $('#generate-password').click(function() {
                var pass = randString(9);
                $('#password').password('show');
                $('#password').password('val', pass);
                $('#password_confirmation').password('show');
                $('#password_confirmation').password('val', pass);
                $('#copy-password').removeClass('hidden');
                ResetPasswordErrors();
            });
            // Reset de datos
            $('#limpiar-datos').click(function() {
                $('#password').password('hide');
                $('#password_confirmation').password('hide');
                $('#copy-password').addClass('hidden');
                ResetPasswordErrors();
            });
            // Copiar password
            $('#copy-password').click(function() {
                $('#password').password('val');
            });
            new Clipboard('#copy-password', {
                text: function(trigger) {
                    return $('#password').password('val');
                }
            });
        });
    </script>
<!-- Partial: campos -- ] -->