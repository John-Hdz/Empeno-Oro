document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.querySelector(".menu-toggle");
    const navLinks = document.querySelector(".nav-links");

    // Mostrar alerta si hay un mensaje en la URL
    const params = new URLSearchParams(window.location.search);
    const msg = params.get("msg");

    if (msg) {
        const mensajes = {
            "login_success": "INICIO DE SESIÓN EXITOSO.",
            "logout_success": "CIERRE DE SESIÓN EXITOSO.",
            "invalid": "CORREO O CONTRASEÑA INCORRECTOS.",
            "unauthorized": "No autorizado para realizar esta acción.",
            "forbidden": "Acceso prohibido.",
            "not_allowed": "Operación no permitida.",
            "session_expired": "La sesión ha expirado. Inicie sesión nuevamente.",
            "password_changed": "Contraseña cambiada correctamente.",
            "email_sent": "Correo electrónico enviado correctamente.",
            "email_not_found": "CORREO ELECTRONICO O CONTRASEÑA INCORRECTOS."
        };

        if (mensajes[msg]) {
            alert(mensajes[msg]);
        }
    }
});
