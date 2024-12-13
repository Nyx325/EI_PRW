(async function checkAuthEarly() {
  const host = document.location.host;
  const authRoute = `http://${host}/src/routes/authRoutes.php`;

  const res = await fetch(authRoute);

  if (res.status === 200) window.location.replace(`http://${host}/`);
  else if (res.status !== 401)
    console.error("Error al verificar autenticación");
})();