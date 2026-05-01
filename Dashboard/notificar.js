// ============================================
// NOTIFICAR.JS - COMPLETO CON BUSQUEDA INCORPORADA - PARTE 1
// Variables, Destinatarios y Busqueda de Estudiantes
// ============================================

let tipoActual = 'merito';
let actividadSeleccionada = null;
let actividadesAgregadas = [];

// ============================================
// FUNCIONES DE DESTINATARIOS
// ============================================
function actualizarDestinatariosInput() {
    const tags = document.querySelectorAll('.destinatario-tag');
    const destinatarios = [];
    
    tags.forEach(tag => {
        if (tag.dataset.id) {
            destinatarios.push({
                id: tag.dataset.id,
                nombre: tag.dataset.nombre,
                ci: tag.dataset.ci
            });
        }
    });
    
    const destinatariosInput = document.getElementById('destinatariosInput');
    if (destinatariosInput) {
        destinatariosInput.value = JSON.stringify(destinatarios);
    }
    
    verificarFormulario();
    
    const emptyMsg = document.querySelector('.empty-destinatarios');
    if (emptyMsg) {
        emptyMsg.style.display = destinatarios.length > 0 ? 'none' : 'block';
    }
}

function agregarDestinatario(id, nombre, ci) {
    const existentes = document.querySelectorAll('.destinatario-tag');
    for (let tag of existentes) {
        if (tag.dataset.id === String(id)) {
            alert('Este estudiante ya esta en la lista');
            return;
        }
    }
    
    const emptyMsg = document.querySelector('.empty-destinatarios');
    if (emptyMsg) emptyMsg.remove();
    
    const destinatariosList = document.getElementById('destinatariosList');
    const tag = document.createElement('div');
    tag.className = 'destinatario-tag';
    tag.dataset.id = id;
    tag.dataset.nombre = nombre;
    tag.dataset.ci = ci;
    tag.innerHTML = '<span>' + nombre + ' (' + ci + ')</span><i class="fas fa-times-circle" onclick="this.parentElement.remove(); actualizarDestinatariosInput();"></i>';
    
    destinatariosList.appendChild(tag);
    actualizarDestinatariosInput();
    
    const buscarUsuario = document.getElementById('buscarUsuario');
    const searchResults = document.getElementById('searchResults');
    if (buscarUsuario) buscarUsuario.value = '';
    if (searchResults) searchResults.classList.remove('show');
}

window.actualizarDestinatariosInput = actualizarDestinatariosInput;
window.agregarDestinatario = agregarDestinatario;

// ============================================
// FUNCION DE BUSQUEDA DE ESTUDIANTES (CORREGIDA)
// ============================================
function buscarEstudiantes(query, callback) {
    const xhr = new XMLHttpRequest();
    // CORREGIDO: Ruta sin "funciones/" porque ya estamos en Dashboard/
    xhr.open('GET', 'buscar_usuario.php?q=' + encodeURIComponent(query), true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                callback(data, null);
            } catch (e) {
                callback(null, 'Error al procesar la respuesta');
            }
        } else {
            callback(null, 'Error de conexion: ' + xhr.status);
        }
    };
    
    xhr.onerror = function() {
        callback(null, 'Error de conexion');
    };
    
    xhr.timeout = 10000;
    xhr.send();
}

// ============================================
// INICIALIZACION CUANDO EL DOM ESTA LISTO
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    
    const selectCategoria = document.getElementById('selectCategoria');
    const selectActividad = document.getElementById('selectActividad');
    const cantidadInput = document.getElementById('cantidad');
    const btnAgregarActividad = document.getElementById('btnAgregarActividad');
    const actividadesList = document.getElementById('actividadesList');
    const actividadesInput = document.getElementById('actividadesInput');
    const tipoBtns = document.querySelectorAll('.tipo-btn');
    const btnSubmit = document.getElementById('btnSubmit');
    const notificarForm = document.getElementById('notificarForm');
    const destinatariosInput = document.getElementById('destinatariosInput');
    const buscarUsuario = document.getElementById('buscarUsuario');
    const searchResults = document.getElementById('searchResults');
    
    const btnCambiarNotificador = document.getElementById('btnCambiarNotificador');
    const notificadorActual = document.getElementById('notificadorActual');
    const notificadorTemporal = document.getElementById('notificadorTemporal');
    const btnCancelarTemp = document.getElementById('btnCancelarTemp');
    const btnVerificarTemp = document.getElementById('btnVerificarTemp');
    const tempNombre = document.getElementById('tempNombre');
    const tempPassword = document.getElementById('tempPassword');
    const idStarInput = document.getElementById('id_star');
    const tipoNotificadorInput = document.getElementById('tipo_notificador');
    
    function verificarFormulario() {
        const destValue = destinatariosInput ? (destinatariosInput.value || '[]') : '[]';
        const destinatarios = JSON.parse(destValue);
        if (btnSubmit) {
            btnSubmit.disabled = !(destinatarios.length > 0 && actividadesAgregadas.length > 0);
        }
    }
    window.verificarFormulario = verificarFormulario;
    
    // ============================================
    // BUSQUEDA DE ESTUDIANTES
    // ============================================
    if (buscarUsuario && searchResults) {
        let timeout;
        buscarUsuario.oninput = function() {
            clearTimeout(timeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.classList.remove('show');
                searchResults.innerHTML = '';
                return;
            }
            
            searchResults.innerHTML = '<p class="search-empty">Buscando...</p>';
            searchResults.classList.add('show');
            
            timeout = setTimeout(function() {
                buscarEstudiantes(query, function(data, error) {
                    if (error) {
                        searchResults.innerHTML = '<p class="search-empty">' + error + '</p>';
                        searchResults.classList.add('show');
                        return;
                    }
                    
                    if (!data || !data.length) {
                        searchResults.innerHTML = '<p class="search-empty">No se encontraron estudiantes</p>';
                    } else {
                        let html = '';
                        data.forEach(function(u) {
                            html += '<div class="search-result-item" onclick="agregarDestinatario(\'' + u.id + '\', \'' + u.nombre + ' ' + u.apellidos + '\', \'' + u.ci + '\')">';
                            html += '<div class="search-result-info">';
                            html += '<span class="search-result-name">' + u.nombre + ' ' + u.apellidos + '</span>';
                            html += '<span class="search-result-details">CI: ' + u.ci + ' | ID: ' + u.id + '</span>';
                            html += '</div>';
                            html += '<i class="fas fa-plus-circle" style="color: #10b981; font-size: 1.3em;"></i>';
                            html += '</div>';
                        });
                        searchResults.innerHTML = html;
                    }
                    searchResults.classList.add('show');
                });
            }, 300);
        };
        
        document.onclick = function(e) {
            if (buscarUsuario && searchResults) {
                if (!buscarUsuario.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.remove('show');
                }
            }
        };
    }

    // ============================================
    // CUENTA TEMPORAL
    // ============================================
    
    if (btnCambiarNotificador && notificadorActual && notificadorTemporal) {
        btnCambiarNotificador.onclick = function() {
            notificadorActual.style.display = 'none';
            notificadorTemporal.style.display = 'block';
        };
    }
    
    if (btnCancelarTemp && notificadorActual && notificadorTemporal) {
        btnCancelarTemp.onclick = function() {
            notificadorTemporal.style.display = 'none';
            notificadorActual.style.display = 'flex';
            if (tempNombre) tempNombre.value = '';
            if (tempPassword) tempPassword.value = '';
            if (idStarInput && typeof usuarioActual !== 'undefined') {
                idStarInput.value = usuarioActual.id;
            }
            if (tipoNotificadorInput) tipoNotificadorInput.value = 'cuenta';
        };
    }
    
    if (btnVerificarTemp) {
        btnVerificarTemp.onclick = function() {
            const nombre = tempNombre ? tempNombre.value.trim() : '';
            const password = tempPassword ? tempPassword.value : '';
            
            if (!nombre || !password) {
                alert('Completa todos los campos');
                return;
            }
            
            // CORREGIDO: Ruta sin "funciones/"
            fetch('verificar_notificador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nombre: nombre, password: password })
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    if (idStarInput) idStarInput.value = data.id;
                    if (tipoNotificadorInput) tipoNotificadorInput.value = 'temporal';
                    
                    if (notificadorTemporal) notificadorTemporal.style.display = 'none';
                    if (notificadorActual) {
                        notificadorActual.style.display = 'flex';
                        const spanElement = notificadorActual.querySelector('.notificador-info span');
                        const cargoElement = notificadorActual.querySelector('.notificador-cargo');
                        const strongElement = notificadorActual.querySelector('strong');
                        
                        if (spanElement) spanElement.textContent = nombre;
                        if (cargoElement) cargoElement.textContent = data.cargo || 'Temporal';
                        if (strongElement) strongElement.textContent = 'Cuenta Temporal';
                    }
                    
                    if (tempNombre) tempNombre.value = '';
                    if (tempPassword) tempPassword.value = '';
                    
                    alert('Cuenta temporal verificada correctamente');
                } else {
                    alert('Credenciales incorrectas');
                }
            })
            .catch(function(err) {
                alert('Error al verificar la cuenta');
            });
        };
    }
    
    // ============================================
    // BUSCADOR DE ACTIVIDADES
    // ============================================
    const buscarActividad = document.getElementById('buscarActividad');
    const actividadSearchResults = document.getElementById('actividadSearchResults');
    
    if (buscarActividad && actividadSearchResults) {
        buscarActividad.oninput = function() {
            const query = this.value.trim().toLowerCase();
            
            if (query.length < 1) {
                actividadSearchResults.style.display = 'none';
                actividadSearchResults.innerHTML = '';
                return;
            }
            
            const catalogo = tipoActual === 'merito' ? catalogoMeritos : catalogoDemeritos;
            const categoriaFiltro = selectCategoria ? selectCategoria.value : '';
            
            let resultados = catalogo.filter(function(item) {
                const textoBusqueda = tipoActual === 'merito' 
                    ? (item.causa + ' ' + item.categoria).toLowerCase()
                    : (item.falta + ' ' + item.categoria).toLowerCase();
                
                const coincideCategoria = !categoriaFiltro || item.categoria === categoriaFiltro;
                return textoBusqueda.includes(query) && coincideCategoria;
            });
            
            if (resultados.length === 0) {
                actividadSearchResults.innerHTML = '<p class="search-empty">No se encontraron actividades</p>';
            } else {
                actividadSearchResults.innerHTML = resultados.map(function(item) {
                    const nombre = tipoActual === 'merito' ? item.causa : item.falta;
                    const valor = tipoActual === 'merito' ? item.meritos : item.demeritos_10mo;
                    const bgColor = tipoActual === 'merito' ? '#d1fae5' : '#fee2e2';
                    const textColor = tipoActual === 'merito' ? '#065f46' : '#991b1b';
                    const itemStr = JSON.stringify(item).replace(/"/g, '&quot;');
                    
                    return '<div class="actividad-search-item" onclick="seleccionarActividadDesdeBusqueda(' + itemStr + ')">' +
                        '<div class="actividad-search-info">' +
                        '<div class="actividad-search-nombre">' + nombre + '</div>' +
                        '<div class="actividad-search-categoria">' + item.categoria + '</div>' +
                        '</div>' +
                        '<div class="actividad-search-valor" style="background: ' + bgColor + '; color: ' + textColor + ';">' +
                        (tipoActual === 'merito' ? '+' : '-') + valor +
                        '</div>' +
                        '</div>';
                }).join('');
            }
            
            actividadSearchResults.style.display = 'block';
        };
    }
    
    function seleccionarActividadDesdeBusqueda(itemData) {
        actividadSeleccionada = itemData;
        
        if (selectCategoria && itemData.categoria) {
            selectCategoria.value = itemData.categoria;
            cargarActividades(itemData.categoria);
        }
        
        if (selectActividad) {
            setTimeout(function() {
                selectActividad.value = JSON.stringify(itemData);
            }, 50);
        }
        
        if (cantidadInput) {
            cantidadInput.value = tipoActual === 'merito' ? itemData.meritos : 
                                 (parseInt(itemData.demeritos_10mo) || 1);
        }
        
        if (actividadSearchResults) actividadSearchResults.style.display = 'none';
        if (buscarActividad) buscarActividad.value = '';
    }
    window.seleccionarActividadDesdeBusqueda = seleccionarActividadDesdeBusqueda;
    
    function cargarCategorias() {
        if (!selectCategoria) return;
        const catalogo = tipoActual === 'merito' ? catalogoMeritos : catalogoDemeritos;
        const categorias = [...new Set(catalogo.map(function(i) { return i.categoria; }))];
        
        selectCategoria.innerHTML = '<option value="">Todas las categorias</option>';
        categorias.sort().forEach(function(cat) {
            const option = document.createElement('option');
            option.value = cat;
            option.textContent = cat;
            selectCategoria.appendChild(option);
        });
    }
    
    function cargarActividades(categoria) {
        if (!selectActividad) return;
        const catalogo = tipoActual === 'merito' ? catalogoMeritos : catalogoDemeritos;
        const actividades = categoria ? catalogo.filter(function(i) { return i.categoria === categoria; }) : catalogo;
        
        selectActividad.innerHTML = '<option value="">Selecciona una actividad</option>';
        actividades.forEach(function(act) {
            const option = document.createElement('option');
            option.value = JSON.stringify(act);
            option.textContent = tipoActual === 'merito' 
                ? act.causa + ' (' + act.meritos + ')'
                : act.falta + ' (' + act.demeritos_10mo + ')';
            selectActividad.appendChild(option);
        });
    }
    
    function renderActividadesList() {
        if (!actividadesList) return;
        
        if (!actividadesAgregadas.length) {
            actividadesList.innerHTML = '<p class="empty-actividades">No hay actividades agregadas</p>';
            return;
        }
        
        actividadesList.innerHTML = actividadesAgregadas.map(function(act, i) {
            const bg = act.tipo === 'merito' ? '#d1fae5' : '#fee2e2';
            const border = act.tipo === 'merito' ? '#10b981' : '#ef4444';
            const color = act.tipo === 'merito' ? '#065f46' : '#991b1b';
            
            return '<div class="actividad-tag" style="background: ' + bg + '; border-left: 4px solid ' + border + ';">' +
                '<div>' +
                '<div class="actividad-tag-nombre">' + act.nombre + '</div>' +
                '<div class="actividad-tag-categoria">' + act.categoria + '</div>' +
                '</div>' +
                '<div class="actividad-tag-actions">' +
                '<span style="color: ' + color + '; font-weight: 700;">' + (act.tipo === 'merito' ? '+' : '-') + act.cantidad + '</span>' +
                '<i class="fas fa-trash-alt" onclick="eliminarActividad(' + i + ')"></i>' +
                '</div>' +
                '</div>';
        }).join('');
    }
    
    function actualizarActividadesInput() {
        if (actividadesInput) {
            actividadesInput.value = JSON.stringify(actividadesAgregadas);
        }
    }
    
    function eliminarActividad(index) {
        actividadesAgregadas.splice(index, 1);
        renderActividadesList();
        actualizarActividadesInput();
        verificarFormulario();
    }
    window.eliminarActividad = eliminarActividad;
    
    if (tipoBtns.length > 0) {
        tipoBtns.forEach(function(btn) {
            btn.onclick = function() {
                tipoBtns.forEach(function(b) { b.classList.remove('active'); });
                this.classList.add('active');
                tipoActual = this.dataset.tipo;
                cargarCategorias();
                cargarActividades('');
                if (buscarActividad) buscarActividad.value = '';
                if (actividadSearchResults) actividadSearchResults.style.display = 'none';
            };
        });
    }
    
    if (selectCategoria) {
        selectCategoria.onchange = function() {
            cargarActividades(this.value);
            if (buscarActividad) {
                buscarActividad.value = '';
                if (actividadSearchResults) actividadSearchResults.style.display = 'none';
            }
        };
    }
    
    if (selectActividad) {
        selectActividad.onchange = function() {
            if (this.value) {
                try {
                    actividadSeleccionada = JSON.parse(this.value);
                    if (cantidadInput) {
                        cantidadInput.value = tipoActual === 'merito' ? actividadSeleccionada.meritos : 
                                             (parseInt(actividadSeleccionada.demeritos_10mo) || 1);
                    }
                } catch (e) {}
            }
        };
    }
    
    if (btnAgregarActividad) {
        btnAgregarActividad.onclick = function() {
            if (!actividadSeleccionada && selectActividad && selectActividad.value) {
                try {
                    actividadSeleccionada = JSON.parse(selectActividad.value);
                } catch (e) {}
            }
            
            if (!actividadSeleccionada) {
                alert('Selecciona una actividad');
                return;
            }
            
            const cantidad = parseInt(cantidadInput ? cantidadInput.value : 1) || 1;
            
            actividadesAgregadas.push({
                tipo: tipoActual,
                categoria: selectCategoria ? (selectCategoria.value || actividadSeleccionada.categoria) : actividadSeleccionada.categoria,
                actividad_id: actividadSeleccionada.id,
                nombre: tipoActual === 'merito' ? actividadSeleccionada.causa : actividadSeleccionada.falta,
                cantidad: cantidad
            });
            
            renderActividadesList();
            actualizarActividadesInput();
            verificarFormulario();
            
            actividadSeleccionada = null;
            if (selectActividad) selectActividad.value = '';
            if (cantidadInput) cantidadInput.value = 1;
        };
    }
    
    if (notificarForm) {
        notificarForm.onsubmit = function(e) {
            e.preventDefault();
            
            const confirmModal = document.getElementById('confirmModal');
            const confirmResumen = document.getElementById('confirmResumen');
            if (!confirmModal || !confirmResumen) return;
            
            const destValue = destinatariosInput ? (destinatariosInput.value || '[]') : '[]';
            const destinatarios = JSON.parse(destValue);
            const fecha = document.getElementById('fecha') ? document.getElementById('fecha').value : '';
            const hora = document.getElementById('hora') ? document.getElementById('hora').value : '';
            const observaciones = document.querySelector('textarea[name="observaciones"]') ? 
                                 (document.querySelector('textarea[name="observaciones"]').value || 'Ninguna') : 'Ninguna';
            
            let notificadorNombre = 'Usuario';
            let notificadorCargo = 'estudiante';
            
            if (notificadorActual) {
                const spanEl = notificadorActual.querySelector('.notificador-info span');
                const cargoEl = notificadorActual.querySelector('.notificador-cargo');
                if (spanEl) notificadorNombre = spanEl.textContent;
                if (cargoEl) notificadorCargo = cargoEl.textContent;
            }
            
            let html = '';
            html += '<div class="confirm-section"><h3>Destinatarios (' + destinatarios.length + ')</h3><div class="confirm-tags">';
            destinatarios.forEach(function(d) { html += '<span>' + d.nombre + '</span>'; });
            html += '</div></div>';
            
            html += '<div class="confirm-section"><h3>Actividades (' + actividadesAgregadas.length + ')</h3>';
            actividadesAgregadas.forEach(function(a) {
                html += '<div class="confirm-actividad ' + a.tipo + '"><strong>' + a.nombre + '</strong><br><small>' + a.categoria + ' | ' + (a.tipo === 'merito' ? '+' : '-') + a.cantidad + '</small></div>';
            });
            html += '</div>';
            
            html += '<div class="confirm-section"><h3>Fecha y Hora</h3><p><i class="fas fa-calendar"></i> ' + fecha + ' | <i class="fas fa-clock"></i> ' + hora + '</p></div>';
            html += '<div class="confirm-section"><h3>Notificado por</h3><p><i class="fas fa-user"></i> ' + notificadorNombre + ' (' + notificadorCargo + ')</p></div>';
            html += '<div class="confirm-section"><h3>Observaciones</h3><p>' + observaciones + '</p></div>';
            
            confirmResumen.innerHTML = html;
            confirmModal.classList.add('show');
            
            document.getElementById('btnCancelarConfirm').onclick = function() { confirmModal.classList.remove('show'); };
            document.getElementById('btnConfirmarEnvio').onclick = function() {
                confirmModal.classList.remove('show');
                
                // CORREGIDO: Ruta sin "funciones/"
                fetch('notificar_procesar.php', {
                    method: 'POST',
                    body: new FormData(notificarForm)
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        document.getElementById('successModal').classList.add('show');
                        
                        actividadesAgregadas = [];
                        renderActividadesList();
                        actualizarActividadesInput();
                        document.querySelectorAll('.destinatario-tag').forEach(function(t) { t.remove(); });
                        actualizarDestinatariosInput();
                        
                        if (tipoNotificadorInput && tipoNotificadorInput.value === 'temporal') {
                            if (idStarInput && typeof usuarioActual !== 'undefined') idStarInput.value = usuarioActual.id;
                            if (tipoNotificadorInput) tipoNotificadorInput.value = 'cuenta';
                            if (notificadorActual) {
                                const spanEl = notificadorActual.querySelector('.notificador-info span');
                                const cargoEl = notificadorActual.querySelector('.notificador-cargo');
                                const strongEl = notificadorActual.querySelector('strong');
                                if (spanEl && typeof usuarioActual !== 'undefined') spanEl.textContent = usuarioActual.nombre;
                                if (cargoEl && typeof usuarioActual !== 'undefined') cargoEl.textContent = usuarioActual.cargo;
                                if (strongEl) strongEl.textContent = 'Yo (Cuenta actual)';
                            }
                        }
                    } else {
                        alert('Error: ' + (data.message || 'No se pudo procesar'));
                    }
                })
                .catch(function(err) { alert('Error de conexion'); });
            };
            
            confirmModal.onclick = function(e) { if (e.target === confirmModal) confirmModal.classList.remove('show'); };
        };
    }
    
    cargarCategorias();
    cargarActividades('');
    actualizarActividadesInput();
    actualizarDestinatariosInput();
    
    const msgExito = document.getElementById('mensajeExito');
    if (msgExito) {
        setTimeout(function() {
            msgExito.style.transition = 'opacity 0.5s';
            msgExito.style.opacity = '0';
            setTimeout(function() { msgExito.remove(); }, 500);
        }, 3000);
    }
});