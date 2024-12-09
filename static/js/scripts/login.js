import Alert from "../components/alert.js";

document.addEventListener("DOMContentLoaded", ()=>{
    const alert = new Alert("alert");
    const usrI = document.getElementById("usr-input");
    const pwdI = document.getElementById("pwd-input");
    const form = document.getElementById("login-form");

    form.addEventListener("submit", async (e)=>{
        e.preventDefault();
        
        const usr = usrI.value.trim();
        const pwd = pwdI.value.trim();

        const url = "http://localhost:3000/src/routes/AuthRoutes.php";

        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({usr, pwd})
        });

        console.log("A");
        if(response.status>=400){
            alert.setMessage(await response.json());
            alert.setVisible(true);
            return;
        }

    })
})