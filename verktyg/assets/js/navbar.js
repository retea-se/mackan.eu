// navbar.js - v1

console.log("navbar.js - v1 laddad");

fetch("navbar.html")
  .then(res => res.text())
  .then(html => {
    const wrapper = document.createElement("div");
    wrapper.innerHTML = html;
    document.body.insertBefore(wrapper.firstElementChild, document.body.firstChild);
    console.log("🧭 Navigationsfält infogat");
  })
  .catch(err => console.warn("Kunde inte ladda navbar:", err));
