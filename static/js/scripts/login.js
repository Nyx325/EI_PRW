import Alert from "../components/alert.js";

document.addEventListener("DOMContentLoaded", () => {
  const host = document.location.host;
  const authRoute = `http://${host}/src/routes/authRoutes.php`;
  const alert = new Alert("alert");
  const btn = document.getElementById("login-btn");
  const usrI = document.getElementById("usr-input");
  const pwdI = document.getElementById("pwd-input");

  btn.addEventListener("click", async () => {
    const email = usrI.value.trim();
    const pwd = pwdI.value.trim();

    const auth = await fetch(authRoute, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        email,
        pwd,
      }),
    });

    if (auth.status === 401) {
      const e = await auth.json();
      alert.setMessage(e.error);
      alert.setVisible(true);
      return;
    } else if (!auth.ok) {
      console.error("Error al consultar ", authRoute);
      return;
    }

    alert.setVisible(false);
    window.location.replace(`http://${host}/index.html`);
  });
});
