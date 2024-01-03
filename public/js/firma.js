$(document).ready(function() {
    bsCustomFileInput.init();
    if (backFirma.inpFirma == '') {
        img.src = '/img/noFirma.jpg';
        img.onload = function() {
            ctx.drawImage(img, 0, 0, img.width, img.height);
            $('#loading').hide();
        }
    } else{
        $('#escala').val(backFirma.escFirma);
        $('#escalaN').html((backFirma.escFirma * 2)+"%");
        let escala = (backFirma.escFirma / 100) * 2;
        img.src = backFirma.inpFirma;
        img.onload = function() {
            ctx.drawImage(img, 0, 0, img.width*escala, img.height*escala);
            noCopiar.src = '/img/noCopiar.png';
            noCopiar.onload = function() {
                ctx.drawImage(noCopiar, 0, 0, noCopiar.width, noCopiar.height);
                $('#loading').hide();
            }
        }
    }
});

function GuardarFirma(){
    if (backFirma.inpFirma != '' || ValidarCampo('inputFirma')) {
        let canvas = document.getElementById('canvas');
        let fullQuality = canvas.toDataURL('image/jpeg', 1.0);
        $('#canvaFirma').val(fullQuality);
        $('#escalaFirma').val($('#escala').val());
        let datos = new FormData(document.getElementById('formFirma'));
        _RQ('POST','/back/guardar_frima', datos, function(result) {$('#loading').hide();
            _MSJ(result.tipo, result.txt, function() {
                if (retorno == "") {
                    location.reload();
                } else {
                    window.location.pathname = retorno;
                }
            });
        }, true);
    }
}

$('#escala').on('input', function (event) {
    let escala = (event.target.value / 100) * 2;
    ctx.clearRect(0, 0, 600, 200);
    ctx.fillStyle = "rgb(255,255,255)";
    ctx.fillRect(0,0,600,200);
    ctx.drawImage(img, 0, 0, img.width*escala, img.height*escala);
    ctx.drawImage(noCopiar, 0, 0, noCopiar.width, noCopiar.height);
    $('#escalaN').html((event.target.value * 2)+"%");
});

$('#inputFirma').on('click', function (event) {
    ctx.clearRect(0, 0, 600, 200);
    $('#escala').val(50);
    $('#escalaN').html("100%");
});

var ctx = document.getElementById('canvas').getContext('2d');
var img = new Image;
var noCopiar = new Image;
$('#inputFirma').on('change', function (event) {
    if (ValidarCampo('inputFirma')) {
        img.src = URL.createObjectURL(event.target.files[0]);
        img.onload = function() {
            ctx.drawImage(img, 0, 0, img.width, img.height);
            noCopiar.src = '/img/noCopiar.png';
            noCopiar.onload = function() {
                ctx.drawImage(noCopiar, 0, 0, noCopiar.width, noCopiar.height);
            }
        }
    }
});
