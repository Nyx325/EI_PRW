(async function checkAuthEarly() {
  const host = document.location.host;
  const authRoute = `http://${host}/src/routes/authRoutes.php`;

  const res = await fetch(authRoute);

  if (res.status === 401) window.location.replace(`http://${host}/static/html/login.html`);
  else if (res.status !== 200)
    console.error("Error al verificar autenticación");
})();