"use strict";

function signup() {
    let employee_no = document.getElementById("employee_no").value;
    let user_name = document.getElementById("user_name").value;
    let user_email = document.getElementById("user_email").value;

    let user_password = document.getElementById("user_password").value;
    // let confirm_password = document.getElementById("confirm_user_password").value;

    let formdata = {
        "employee_no": employee_no,
        "user_name": user_name,
        "user_email": user_email,
        "user_password": user_password
    }
    console.log(formdata);

    fetch('http://127.0.0.1:5000/api/v1/signup', {
        method: 'post',
        headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*"
        },
        body: JSON.stringify(formdata)
    }).then(response => response.json())
        .then(data => {
            if (data.message === "User " + user_name + " successfully created") {
                console.log(user_name + ' successfully registered. Login to proceed');
                location.href = "login.html"
            } else {
                console.log("failed")
            }
        })
}

function login() {
    let username = document.getElementById("login_name").value;
    let password = document.getElementById("login_password").value;
    console.log(username + password);
    let formdata = {
        "user_name": username,
        "user_password": password
    }
    fetch('http://127.0.0.1:5000/api/v1/login', {
        method: 'post',
        headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*"
        },
        body: JSON.stringify(formdata)
    }).then(res => res.json())
        .then((data) => {
            console.log((data))
            if (data.message === "You have successfully been logged in as " + username) {
                localStorage.setItem("username", username);
                console.log(username + ' successfully registered. Login to proceed');
                location.href = "../index1.html"
            } else {
                console.log("oops, failed!")
            }

        })
}
