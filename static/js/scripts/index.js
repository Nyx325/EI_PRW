async function checkAuthentication() {
  const host = document.location.host;
  const authRoute = `http://${host}/src/routes/authRoutes.php`;
  const res = await fetch(authRoute);

  console.log(res.status);

  if (res.status === 401) {
    window.location.replace(`http://${host}/static/html/login.html`);
    return;
  } else if (!res.ok) {
    console.error(await res.json());
    return;
  }

  document.addEventListener("DOMContentLoaded", () => {
  });
}

checkAuthentication();
