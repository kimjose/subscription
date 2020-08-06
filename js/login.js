const loginBtn = document.getElementById("loginBtn");
const username = document.getElementById("username");
const password = document.getElementById("password");
loginBtn.addEventListener("click", ()=>{
    console.log(password.value);
    login();
});

document.getElementById("myForm").addEventListener("submit", function (event) {
    event.preventDefault()
});


function login() {
    var name = username.value;
    var pass = password.value;
    console.log(name+" "+pass);
    if (name != "" && pass != "") {
        $.ajax
            ({
                type: 'post',
                url: 'file',
                data: {
                    submit: "submit",
                    username: name,
                    password: pass,
                },
                success: function (response) {
                    if (response == "success") {
                        window.location.href = "index";
                    }
                    else {
                        alert(response);
                        //alert("Wrong Details");
                    }
                }
            });
    }

    else {
        alert("Please Fill All The Details");
    }

    return false;
}