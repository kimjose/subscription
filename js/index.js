
const table = document.getElementById("table");
let tableData = table.getElementsByTagName("tr");

const liTransactions = document.getElementById("li_transactions");
liTransactions.addEventListener("click", ()=>{
    var headers = "<th>Transaction Id</th><th>Name</th><th>Mobile Number</th><th>Amount</th>"
    $.ajax ({
        type: 'get',
        url: 'data_script?request=transactions',
        success: function (response) {
            var mResponse = JSON.parse(response);
            var code = mResponse.code;
            if (code == 0) {
                table.innerHTML = headers;
                var data = mResponse.data;
                for (let index = 0; index < data.length; index++) {
                    const t = data[index];
                    var row = table.insertRow(index+1);
                    var transCell = row.insertCell(0);
                    var nameCell = row.insertCell(1);
                    var numCell = row.insertCell(2);
                    var amountCell = row.insertCell(3);
                    transCell.innerHTML = t.TransID;
                    nameCell.innerHTML = t.FirstName +" "+ t.MiddleName +" "+ t.LastName;
                    numCell.innerHTML = t.MSISDN;
                    amountCell.innerHTML = t.TransAmount;
                }
                tableData = table.getElementsByTagName("tr");
            } else {
                var message = mResponse.message;
                alert(message);
            }
        }
    })
    liTransactions.parentElement.querySelector(".current").classList.toggle("current");
    liTransactions.classList.toggle("current")
    document.getElementById("newClient").style.display = "none";
});

const liActivations = document.getElementById("li_activations");
liActivations.addEventListener("click", ()=>{
    $.ajax ({
        type:'get',
        url:'data_script?request=activations',
        success: function(response) {
            var mResponse = JSON.parse(response);
            var code = mResponse.code;
            if (code == 0) {
                var headers = "<th>Transaction ID</th><th>Client</th><th>Days</th><th>Expires On</th><th>Created At</th>";
                table.innerHTML = headers;
                var data = mResponse.data;
                for (let index = 0; index < data.length; index++) {
                    const a = data[index];
                    var row = table.insertRow(index+1);
                    var transCell = row.insertCell(0);
                    var clientCell = row.insertCell(1);
                    var daysCell = row.insertCell(2);
                    var expiresCell = row.insertCell(3);
                    var createdCell = row.insertCell(4);
                    transCell.innerHTML = a.transacId;
                    clientCell.innerHTML = a.clientName;
                    daysCell.innerHTML = a.days;
                    expiresCell.innerHTML = a.expiresOn;
                    createdCell.innerHTML = a.created_at;
                }
            } else {
                var message = mResponse.message;
                alert(message);
            }
        }
    });
    liActivations.parentElement.querySelector(".current").classList.toggle("current");
    liActivations.classList.toggle("current")
    document.getElementById("newClient").style.display = "none";
});

const liClients = document.getElementById("li_clients");
liClients.addEventListener("click", ()=>{
    $.ajax ({
        type : "get",
        url: 'data_script?request=clients',
        success: function(response){
            let mResponse = JSON.parse(response);
            var code = mResponse.code;
            if (code == 0) {
                var headers = "<th>Name</th><th>Email</th><th>Phone Number</th><th>Location</th><th>Business Description</th>";
                table.innerHTML = headers;
                let data = mResponse.data;
                for (let i = 0; i < data.length; i++) {
                    const c = data[i];
                    var row = table.insertRow(i + 1);
                    let nameCell = row.insertCell(0);
                    let emailCell = row.insertCell(1);
                    let numberCell = row.insertCell(2);
                    let locationCell = row.insertCell(3);
                    let descCell = row.insertCell(4);
                    nameCell.innerHTML = c.name;
                    emailCell.innerHTML = c.email;
                    numberCell.innerHTML = c.phoneNumber;
                    locationCell.innerHTML = c.location;
                    descCell.innerHTML = c.businessDescription;
                }
            } else {
                alert(mResponse.message);
            }
        }
    });
    liClients.parentElement.querySelector(".current").classList.toggle("current");
    liClients.classList.toggle("current");
    document.getElementById("newClient").style.display = "";
});

const inputSearch = document.getElementById("search_input");
inputSearch.addEventListener("input", ()=> {
    console.log(inputSearch.value);
    let searchString = inputSearch.value.toLowerCase();
    var rows = table.getElementsByTagName("tr");
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        row.style.display = "none";
        const rowData = row.getElementsByTagName("td");
        for (let j = 0; j < rowData.length; j++) {
            const element = rowData[j];
            let val = element.innerHTML.toLowerCase();
            if (val.includes(searchString)) {
                row.style.display = "";
                break;
            }
        }
    }
});

const clientBtn = document.getElementById("newClient");
clientBtn.addEventListener("click", ()=>{
    var dialog = document.getElementById("clientDialog");
    dialog.setAttribute("open", "open");
});

document.getElementById("addClient").addEventListener("click", ()=>{
    var dialog = document.getElementById("clientDialog");
    let inputName = document.getElementById("inputName");
    let inputEmail = document.getElementById("inputEmail");
    let inputNumber = document.getElementById("inputNum");
    let inputLoc = document.getElementById("inputLocation");
    let inputDesc = document.getElementById("inputDesc");
    var name = inputName.value;
    var email = inputEmail.value;
    var num = inputNumber.value;
    var location = inputLoc.value;
    var desc = inputDesc.value;
    $.ajax({
        type: "post",
        url: 'data_script?request=add_client',
        data: {
            name : name,
            email : email,
            phoneNumber : num,
            location : location,
            desc : desc,
        },
        success : function (response) {
            let mResponse = JSON.parse(response);
            var code = mResponse.code;
            if (code == 0) {
                console.log(mResponse.message);
                let data = mResponse.data; 
                var headers = "<th>Name</th><th>Email</th><th>Phone Number</th><th>Location</th><th>Business Description</th>";
                table.innerHTML = headers;
                for (let i = 0; i < data.length; i++) {
                    const c = data[i];
                    var row = table.insertRow(i + 1);
                    let nameCell = row.insertCell(0);
                    let emailCell = row.insertCell(1);
                    let numberCell = row.insertCell(2);
                    let locationCell = row.insertCell(3);
                    let descCell = row.insertCell(4);
                    nameCell.innerHTML = c.name;
                    emailCell.innerHTML = c.email;
                    numberCell.innerHTML = c.phoneNumber;
                    locationCell.innerHTML = c.location;
                    descCell.innerHTML = c.businessDescription;
                }
                dialog.removeAttribute("open");
            } else {
                console.log(mResponse.message);
            }
        }
    });
});