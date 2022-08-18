        //Obtengo los elementos select
        // let anyoCreacionSeelect = document.getElementById("anyoCreacionSeelect");
        // let fechaCreacionSeelect = document.getElementById("fechaCreacionSeelect");
        // let regionSelect = document.getElementById("regionSelect");
        // let departamentoSelect = document.getElementById("departamentoSelect");
        // let tecnologiaSelect = document.getElementById("tecnologiaSelect");
        // let tipoOrdenSelect = document.getElementById("tipoOrdenSelect");
        // let categoriaSelect = document.getElementById("categoriaSelect");
        // let subCategoriaSelect = document.getElementById("subcategoriaSelect");
        // let mesCreacionSelect = document.getElementById("mesCreacionSeelect");

        let formulario = document.getElementById("form-data");

        let button = document.getElementById("loadData");

        // anyoCreacionSeelect.addEventListener("change", function() {
        //     formulario.submit();
        // });

        // mesCreacionSelect.addEventListener("change", function() {
        //     formulario.submit();
        // });

        // fechaCreacionSeelect.addEventListener("change", function() {
        //     formulario.submit();
        // });

        // regionSelect.addEventListener("change", function() {
        //     formulario.submit();
        // });

        // departamentoSelect.addEventListener("change", function() {
        //     formulario.submit();
        // });

        // tipoOrdenSelect.addEventListener("change", function() {
        //     formulario.submit();
        // });

        // tecnologiaSelect.addEventListener("change", function() {
        //     formulario.submit();
        // });

        // categoriaSelect.addEventListener("change", function() {
        //     formulario.submit();
        // });

        // subCategoriaSelect.addEventListener("change", function() {
        //     formulario.submit();
        // });

        formulario.addEventListener("change",function(){
            formulario.submit();
        });

        button.addEventListener("click", function() {
            window.open("http://localhost/devSopaV22/scripts/ows/front-cargaDatos-Incidente-tt-crd.php");
        });