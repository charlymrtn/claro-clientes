
// Funciones generales para formas
function randString(size){
    var possible = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!.-%&*$@';
    var text = '';
    for(var i=0; i < size; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
}

