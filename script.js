//var selectedRow = null;
//Clear All fields Limpiar archivos
function clearFields(){
    document.querySelector("#idNumContrato"),value = "";
    document.querySelector("#idNumFianza"),value = "";
    document.querySelector("#idNumProveedor"),value = "";
    document.querySelector("#idNomProveedor"),value = "";
    document.querySelector("#idMontoMin"),value = "";
    document.querySelector("#idMontoMax"),value = "";
    document.querySelector("#idVigenciaIni"),value = "";
    document.querySelector("#idVigenciaFin"),value = "";
}

//Add Data Agregar datos
document.querySelector("#contrat-form").addEventListener("submit" , (e) =>{
    e.preventDefault();

    //Get Form Values Obtener valores del formulario
    const numContrato = document.querySelector("#idNumContrato").value;
    const numFianza = document.querySelector("#idNumFianza").value;
    const numProveedor = document.querySelector("#idNumProveedor").value;
    const nomProveedor = document.querySelector("#idNomProveedor").value;
    const montoMin = document.querySelector("#idMontoMin").value;
    const montoMax = document.querySelector("#idMontoMax").value;
    const vigenciaIni = document.querySelector("#idVigenciaIni").value;
    const vigenciaFin = document.querySelector("#idVigenciaFin").value;

    //validate
    if (numContrato == "" || numFianza == "" || numProveedor == "" || nomProveedor =="" || montoMin =="" || montoMax == "" || vigenciaIni == "" || vigenciaFin == "")
    {
        showAlert("Por favor completa todos los campos")
    }
    else{
        if(selectedRow == null){
            const list = document.querySelector("#contrat-list");
            const row = document.createElement("tr");

            row.innerHTML = `
            <td>${numContrato}</td>
            <td>${numFianza }</td>
            <td>${numProveedor}</td>
            <td>${nomProveedor}</td>
            <td>${montoMin}</td>
            <td>${montoMax}</td>
            <td>${vigenciaIni}</td>
            <td>${vigenciaFin}</td>
            <td>
            <a href="#" class="btn btn-warning btn-sm edit">Modificar</a>
            <a href="#" class="btn btn-danger btn-sm delete">Eliminar</a>
            </td>
            `;
            list.appendChild(row);
            selectedRow = null;
            showAlert("Contrato agregado", " Con exito");
        }
        else {
            selectedRow.children[0].textContent = numContrato;
            selectedRow.children[1].textContent = numFianza;
            selectedRow.children[2].textContent = numProveedor;
            selectedRow.children[3].textContent = nomProveedor;
            selectedRow.children[4].textContent = montoMin;
            selectedRow.children[5].textContent = montoMax;
            selectedRow.children[6].textContent = vigenciaIni;
            selectedRow.children[7].textContent = vigenciaFin;
            selectedRow = null;
            showAlert("La informacion del contrato ha sido modificado")
        }
        clearFields();
    }
});
//Edit data Editar datos
document.querySelector("#contrat-list").addEventListener("click", (e) => {
    target = e.target;
    if(target.classList.contains("edit")){
        selectedRow = target.parentElement.parentElement;
        document.querySelector("#idNumContrato").value = selectedRow.children[0].textContent;
        document.querySelector("#idNumFianza").value = selectedRow.children[1].textContent;
        document.querySelector("#idNumProveedor").value = selectedRow.children[2].textContent;
        document.querySelector("#idNomProveedor").value = selectedRow.children[3].textContent;
        document.querySelector("#idMontoMin").value = selectedRow.children[4].textContent;
        document.querySelector("#idMontoMax").value = selectedRow.children[5].textContent;
        document.querySelector("#idVigenciaIni").value = selectedRow.children[6].textContent;
        document.querySelector("#idVigenciaFin").value = selectedRow.children[7].textContent;

    }
});
//Delete Data Borrar datos

document.querySelector("#contrat-list").addEventListener("click", (e) =>{
    target = e.target;
    if(target.classList.contains("delete"))
    {
        target.parentElement.parentElement.remove();
        showAlert("El dato se ha eliminado", "danger");
    }
});