class Excel{
    constructor(excelFile){
        this.excelFile = excelFile;
    }

    //Retorno solo la cabecera
    header(){
        return this.excelFile[0];
    }

    body(){
        return new rowsCollection(this.excelFile.slice(1,this.excelFile.length));
    }

}

class rowsCollection{
    constructor(body){
        this.body = body;
    }

    //Retorna la primera fila de los archivos
    first(){
        return this.body[0];
    }

    //Retorna el cuerpo entero del archivo
    get(){
        return this.body;
    }

    //Cuenta la cantidad de filas que tiene el arreglo a partir del cuerpo
    count(){
        return this.body.length;
    }

    //Retorno una fila en especifico de todo el cuerpo
    index(i){
        return this.body[i];
    }
}

class ShowExcelTable{
    static print(tableId,excel){
         const table = document.getElementById(tableId);

         excel.header().forEach(title => {
            table.querySelector("thead>tr").innerHTML += `<th scope="col">${title}</th>`;
         });

         let rows = excel.body().count();

         console.log(rows);

         let filaColumnas ="";

         for(let i = 0; i<rows; i++){
            filaColumnas += `<tr>`;
                excel.body().index(i).forEach(columna=>{
                    filaColumnas += `<td>${columna}</td>`
                });
                filaColumnas += `</tr>`;
         }
         table.querySelector("tbody").innerHTML = filaColumnas;
    } 
 }

//Guardo la referencia del elemento input:file
const excelInput = document.getElementById("excel-file");

//Guardo la referencia del elemento button
const boton = document.querySelector("button");

let excel;

//Cuando surja algún cambio en el input este leera la información y la mostrará por pantalla en una tabla
//Como la lectura de datos puede tardar declaro la funcion de forma asyncrona para que esperé la signación de los valores a la varible
excelInput.addEventListener("change",async function(){

    const content = await readXlsxFile(excelInput.files[0]);

    //Declaro una variable con la clase
     //excel = new Excel(content);

        //llamo el metodo estatico de la clase y le paso como parametro el ID de la tabla y la instancia de la clase Excel
       //ShowExcelTable.print("excel-table",excel);

       //Retiro la clase del boton que lo oculta
       boton.classList.remove("state");

});


boton.addEventListener("click",async function(){
    let valorConfirm = confirm("¿Está seguro/a que desea subir esta información a la base de datos?");
    if(valorConfirm){
        boton.classList.add("state");
        alert("Por favor NO interrumpa la carga de la página Web");
        boton.submit();
    }
});
