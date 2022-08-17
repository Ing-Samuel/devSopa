//Obtengo los elementos select

let fechaCreacionSeelect = document.getElementById("fechaCreacionSeelect");

let regionSelect = document.getElementById("regionSelect");

//let tipoOrdenSelect = document.getElementById("tipoOrdenSelect");

let formulario = document.getElementById("form-data");



let anyoCreacionSeelect = document.getElementById("anyoCreacionSeelect");

anyoCreacionSeelect.addEventListener("change",function(){
    formulario.submit();
});

fechaCreacionSeelect.addEventListener("change",function(){
    formulario.submit();
});

regionSelect.addEventListener("change",function(){
    formulario.submit();
});

// tipoOrdenSelect.addEventListener("change",function(){
//     formulario.submit();
// });