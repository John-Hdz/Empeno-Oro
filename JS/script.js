document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.querySelector(".menu-toggle");
    const navLinks = document.querySelector(".nav-links");

    menuToggle.addEventListener("click", function () {
        navLinks.classList.toggle("active");
    });

    // Seleccionar todos los acordeones y configurar eventos
    document.querySelectorAll(".accordion-item").forEach(item => {
        const header = item.querySelector(".accordion-header");
        const toggleBtn = item.querySelector(".toggle-btn");
        const content = item.querySelector(".accordion-content");

        if (!header || !toggleBtn || !content) {
            return;
        }

        header.addEventListener("click", function () {
            content.classList.toggle("active");
            toggleBtn.textContent = content.classList.contains("active") ? "-" : "+";
        });
    });

    // Mensajes de alerta
    const mensajes = {
    "success": "✅ Datos guardados correctamente.",
    "error": "❌ Hubo un error al guardar los cambios.",
    "updated": "🔄 Datos actualizados correctamente.",
    "deleted": "🗑️ Elemento eliminado correctamente.",
    "not_found": "🔍 Elemento no encontrado.",
    "login_success": "🔓 INICIO DE SESIÓN EXITOSO.",
    "invalid": "⚠️ Datos inválidos.",
    "unauthorized": "🚫 No autorizado para realizar esta acción.",
    "forbidden": "⛔ Acceso prohibido.",
    "not_allowed": "🚷 Operación no permitida.",
    "session_expired": "⌛ La sesión ha expirado. Inicie sesión nuevamente.",
    "password_changed": "🔐 Contraseña cambiada correctamente.",
    "email_sent": "📧 Correo electrónico enviado correctamente.",
    "email_not_found": "📭 Correo electrónico no encontrado.",
    "session_does_not_exist": "🔒 Necesitas iniciar sesión para acceder a esta sección.",
    "transaction_success": "💰 Transacción realizada con éxito.",
    "qr_success": "📲 Código QR generado correctamente.",
};

    // Mostrar alerta si hay un mensaje en la URL
    const params = new URLSearchParams(window.location.search);
    const msg = params.get("msg");

    if (msg === "login_success")
    {
        const usuario = "Bienvenido de nuevo"; // puedes insertar el nombre si lo pasas en PHP
        const imagen = "../images/Ilustraciones/people.png"; // asegúrate de que esta ruta exista

        const contenido = `<img src="${imagen}" style='max-width: 200px;' alt="Bienvenido">
                            <p style="text-align: center; font-size: 1.2em;">🖐️ ${usuario}</p>`;

        showModal("Inicio de Sesión Exitoso", contenido, [{ texto: "Continuar", clase: "btn dark" }]);
    }


    // Mostrar el resultado de cotizacion online
    if(msg === "cotizacion")
    {
        const cotizacionNum = parseFloat(params.get("cotizacion"));
        const totalInteresNum = parseFloat(params.get("totalInteres"));
        const cotizacion = Number.isFinite(cotizacionNum) ? cotizacionNum.toFixed(2) : "0.00";
        const totalInteres = Number.isFinite(totalInteresNum) ? totalInteresNum.toFixed(2) : "0.00";
        const contenido = `<img src="../images/Ilustraciones/online.png" style='max-width: 200px;' alt="Bienvenido">
                            <h3 style="text-align: center; font-size: 1.2em;">Puedes obtener: $${cotizacion} 💰</h3>
                            <h3 style="text-align: center; font-size: 1.2em;">Solo pagas: $${totalInteres} de interés 🏷️</h3>`;

        showModal("Cotización para tu Prenda", contenido, [{ texto: "Volver", clase: "btn dark" }]);
    }

    if (mensajes[msg])
    {
        const alertContainer = document.getElementById("alert-container");
        alertContainer.textContent = mensajes[msg];

        alertContainer.style.display = "block";
        alertContainer.style.color = (msg === "success" || msg === "updated" || msg === "login_success" || 
                                    msg === "password_changed" || msg === "email_sent" || 
                                    msg === "transaction_success" || msg=== "qr_success") ? "#155724" : "#721c24";
                                    
        alertContainer.style.backgroundColor = (msg === "success" || msg === "updated" || msg === "login_success" || 
                                            msg === "password_changed" || msg === "email_sent" || 
                                            msg === "transaction_success" || msg=== "qr_success") ? "#d4edda" : "#f8d7da";
        alertContainer.style.width = "100%";

    // Ocultar después de 5 segundos (opcional)
        setTimeout(() =>{ alertContainer.style.display = "none";}, 5000);
    }


    
});

function showModal(titulo, cuerpoHTML, botones = [])
{

    document.getElementById('modalTitle').textContent = titulo;
    document.getElementById('modalBody').innerHTML = cuerpoHTML;
    const footer = document.getElementById('modalFooter');
    footer.innerHTML = '';

    botones.forEach(b =>
    {
        const btn = document.createElement('button');
        btn.textContent = b.texto;
        btn.className = b.clase || 'btn-light';
        btn.onclick = () =>
        {
            closeModal();
            if (typeof b.accion === 'function') b.accion();
        };
        footer.appendChild(btn);
    });
    const modal = document.getElementById('customModal');
    modal.style.display = 'flex';
    modal.style.justifyContent = 'center';
    modal.style.alignItems = 'center';
}


function closeModal() 
{
    document.getElementById('customModal').style.display = 'none';
}

function updateSliderValue(slider, output) {
  const min = parseFloat(slider.min);
  const max = parseFloat(slider.max);
  const val = parseFloat(slider.value);

  const percent = ((val - min) / (max - min)) * 100;
  output.textContent = val;
  output.style.left = `calc(${percent}% + (${8 - percent * 0.05}px))`;
}

document.addEventListener("DOMContentLoaded", () => {
  const kilatajeSlider = document.getElementById("kilataje-slider");
  const kilatajeOutput = document.getElementById("kilataje-output");

  const pesoSlider = document.getElementById("peso-slider");
  const pesoOutput = document.getElementById("peso-output");

  [kilatajeSlider, pesoSlider].forEach((slider, i) => {
    const output = i === 0 ? kilatajeOutput : pesoOutput;
    if (!slider || !output) return;

    updateSliderValue(slider, output);

    slider.addEventListener("input", () => {
      updateSliderValue(slider, output);
    });
  });
});

function submitPostAction(url) {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = url;

  const meta = document.querySelector('meta[name="csrf-token"]');
  if (meta) {
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "csrf_token";
    input.value = meta.getAttribute("content") || "";
    form.appendChild(input);
  }

  document.body.appendChild(form);
  form.submit();
}
