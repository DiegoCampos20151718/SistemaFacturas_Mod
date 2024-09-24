//Get element the modal
var form = document.getElementById("myForm"),
    fianza = document.getElementById("fianza"),
    numProv = document.getElementById("numProveedor"),
    nomProv = document.getElementById("nomProveedor"),
    montMin = document.getElementById("montoMin"),
    montMax = document.getElementById("montoMax"),
    vigIni = document.getElementById("vigenciaIni"),
    vigFin = document.getElementById("vigenciaFin"),
    submitBtn = document.querySelector(".submit"),
    contratInfo = document.getElementById("contrat-list"),
    modal = document.getElementById("contratForm"),
    modalTitle = document.querySelector("#contratForm .modal-title");

    let getData = localStorage.getItem('userContrat') ? JSON.parse(localStorage.getItem('userContrat')) : []
    let isEdit = false, editId
    showInfo();

//Show info
function showInfo() {
    document.querySelectorAll('.employeeDetails').forEach(info => info.remove())
    getData.forEach((element, index) =>{
        let createElement = `<tr class ="employeeDetails">
            <td>${index+1}</td>
            <td>${element.employeefianza}</td>
            <td>${element.employeenumProv}</td>
            <td>${element.employeenomProv}</td>
            <td>${element.employeemontMin}</td>
            <td>${element.employeemontMax}</td>
            <td>${element.employeevigIni}</td>
            <td>${element.employeevigFin}</td>


            <td>
            <button class="btn btn-success" onclick="readInfo('${element.employeefianza}', '${element.employeenumProv}', '
            ${element.employeenomProv}', '${element.employeemontMin}', '${element.employeemontMax}', '${element.employeevigIni}', '
            ${element.employeevigFin}')" data-bs-toggle="modal" 
            data-bs-target="#readData"><i class="bi bi-eye"></i></button>

            <button class="btn btn-primary" onclick="editInfo(${index}, '${element.employeefianza}', '${element.employeenumProv}','
            ${element.employeenomProv}', '${element.employeemontMin}', '${element.employeemontMax}', '${element.employeevigIni}', '
            ${element.employeevigFin}')" data-bs-toggle="modal" 
            data-bs-target="#contratForm"><i class="bi bi-pencil-square"></i></button>

            <button class="btn btn-danger" onclick="deleteInfo(${index})"><i class="bi bi-trash"></i></button>

            </td>
         </tr>`
        contratInfo.innerHTML += createElement
    })
    
}

function readInfo(fianza,numProv,nomProv,montMin,montMax,vigIni,vigFin){
    document.querySelector('#showFianza').value = fianza,
    document.querySelector('#showNumProveedor').value = numProv,
    document.querySelector("#showNomProveedor").value = nomProv,
    document.querySelector("#showMontoMin").value = montMin,
    document.querySelector("#showMontoMax").value = montMax,
    document.querySelector("#showVigenciaIni").value = vigIni,
    document.querySelector("#showVigenciaFin").value = vigFin
}

function deleteInfo(index)
{
    if(confirm("¿Estas seguro de que quieres eliminarlo?")){
        getData.splice(index, 1)
        localStorage.setItem("userContrat", JSON.stringify(getData))
        showInfo()
    }
}

form.addEventListener('submit' , (e) => {
    e.preventDefault()

    const informacion = {
        employeefianza: fianza.value,
        employeenumProv: numProv.value,
        employeenomProv: nomProv.value,
        employeemontMin: montMin.value,
        employeemontMax: montMax.value,
        employeevigIni: vigIni.value,
        employeevigFin: vigFin.value
    }
    if(!isEdit){
        getData.push(informacion)
    }
    else{
        isEdit = false
        getData[editId] = informacion
    }

    localStorage.setItem('userContrat' , JSON.stringify(getData))

    submitBtn.innerText = "Guardar"
    modalTitle.innerHTML = "Completa el formulario"

    showInfo()

    form.reset()

    modal.style.display = "none"
    document.querySelector(".modal-backdrop").remove()
})