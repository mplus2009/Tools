<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#1e3c72">
    <title>Notificar - Sistema Escolar</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f0f4f8; min-height: 100vh; padding-bottom: 30px; }
        
        .header { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); padding: 16px 20px; display: flex; align-items: center; position: sticky; top: 0; z-index: 100; box-shadow: 0 4px 15px rgba(0,0,0,0.12); }
        .header-left { display: flex; align-items: center; gap: 15px; }
        .back-btn { background: rgba(255,255,255,0.15); border: none; width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; text-decoration: none; cursor: pointer; }
        .header-title { color: white; font-size: 1.4em; font-weight: 600; }
        
        .main-content { padding: 20px 16px; max-width: 650px; margin: 0 auto; }
        .form-section { background: white; border-radius: 22px; padding: 22px 18px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
        .section-title { color: #1e3c72; font-size: 1.25em; font-weight: 700; margin-bottom: 18px; padding-bottom: 10px; border-bottom: 2px solid #e8edf2; display: flex; align-items: center; gap: 10px; }
        .section-title i { color: #667eea; font-size: 1.1em; }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; color: #2a5298; font-size: 0.9em; font-weight: 600; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 14px 16px; font-size: 1em; border: 2px solid #e0e0e0; border-radius: 14px; background: white; font-family: 'Poppins', sans-serif; }
        .form-control:focus { outline: none; border-color: #2a5298; }
        .form-row { display: flex; gap: 15px; }
        .form-row .form-group { flex: 1; }
        textarea.form-control { resize: vertical; min-height: 80px; }
        
        .destinatarios-list { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; min-height: 60px; padding: 12px; background: #f8fafc; border-radius: 16px; border: 1px dashed #cbd5e0; }
        .destinatario-tag { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 16px; border-radius: 30px; display: inline-flex; align-items: center; gap: 10px; font-size: 0.9em; }
        .destinatario-tag i { cursor: pointer; opacity: 0.8; font-size: 1.1em; }
        .empty-destinatarios { width: 100%; text-align: center; color: #888; padding: 12px; font-size: 0.95em; }
        .mensaje-exito { width: 100%; background: #10b981; color: white; padding: 12px 16px; border-radius: 14px; text-align: center; font-size: 0.95em; margin-top: 10px; }
        
        .search-box { margin-bottom: 18px; position: relative; }
        .search-input-wrapper { position: relative; }
        .search-input-wrapper i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #999; font-size: 1.1em; z-index: 1; pointer-events: none; }
        .search-input-wrapper input { width: 100%; padding: 15px 16px 15px 48px !important; font-size: 1em; border: 2px solid #e0e0e0; border-radius: 14px; background: white; font-family: 'Poppins', sans-serif; box-sizing: border-box; }
        .search-results { position: absolute; top: 100%; left: 0; right: 0; background: white; border-radius: 14px; margin-top: 8px; max-height: 280px; overflow-y: auto; box-shadow: 0 6px 18px rgba(0,0,0,0.15); display: none; border: 1px solid #e8edf2; z-index: 9999 !important; }
        .search-results.show { display: block !important; }
        .search-result-item { padding: 14px 16px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e8edf2; cursor: pointer; }
        .search-result-item:active { background: #f0f4ff; }
        .search-result-info { display: flex; flex-direction: column; gap: 3px; flex: 1; }
        .search-result-name { font-weight: 600; color: #1e293b; font-size: 1em; }
        .search-result-details { font-size: 0.85em; color: #64748b; }
        .search-empty { padding: 20px; text-align: center; color: #888; font-size: 0.95em; }
        
        .btn-qr-scanner { width: 100%; padding: 15px; background: #f0f4f8; color: #1e3c72; border: 2px dashed #1e3c72; border-radius: 14px; font-size: 1.05em; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; }
        
        .actividades-list { margin-bottom: 18px; min-height: 60px; }
        .actividad-tag { padding: 12px 14px; border-radius: 12px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
        .actividad-tag > div:first-child { flex: 1; min-width: 150px; }
        .actividad-tag-nombre { font-weight: 600; color: #1e293b; margin-bottom: 3px; }
        .actividad-tag-categoria { font-size: 0.8em; color: #64748b; background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 12px; display: inline-block; }
        .actividad-tag-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .actividad-tag-actions i { cursor: pointer; color: #ef4444; font-size: 1.1em; }
        .actividad-tag-valores { display: flex; flex-direction: column; gap: 2px; font-size: 0.8em; }
        .empty-actividades { color: #888; text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e0; font-size: 0.95em; }
        
        .actividad-buscador-container { margin-bottom: 18px; }
        .actividad-search-results { max-height: 320px; overflow-y: auto; margin-top: 8px; border-radius: 14px; background: white; box-shadow: 0 6px 18px rgba(0,0,0,0.1); border: 1px solid #e8edf2; display: none; }
        .actividad-search-item { padding: 14px 16px; border-bottom: 1px solid #e8edf2; cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
        .actividad-search-info { flex: 1; }
        .actividad-search-nombre { font-weight: 600; color: #1e293b; margin-bottom: 4px; }
        .actividad-search-categoria { font-size: 0.8em; color: #64748b; background: #e2e8f0; padding: 3px 10px; border-radius: 12px; display: inline-block; }
        .actividad-search-valor { padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 0.95em; white-space: nowrap; }
        
        .tipo-selector { display: flex; gap: 12px; margin-bottom: 18px; }
        .tipo-btn { flex: 1; padding: 14px; border: 2px solid #e0e0e0; border-radius: 14px; background: white; font-size: 1em; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; }
        .tipo-btn[data-tipo="merito"].active { background: #10b981; border-color: #10b981; color: white; }
        .tipo-btn[data-tipo="demerito"].active { background: #ef4444; border-color: #ef4444; color: white; }
        
        .btn-add-actividad { width: 100%; padding: 15px; background: #1e3c72; color: white; border: none; border-radius: 14px; font-size: 1.05em; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; margin-top: 8px; }
        
        #sliderContainer10mo, #sliderContainer11_12 { display: none; padding: 16px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 14px; margin-bottom: 18px; }
        #sliderContainer10mo label, #sliderContainer11_12 label { display: block; font-weight: 700; color: #856404; margin-bottom: 8px; font-size: 0.95em; }
        .rango-info { display: flex; justify-content: space-between; font-size: 0.85em; color: #856404; margin-bottom: 5px; }
        #sliderContainer10mo input[type="range"], #sliderContainer11_12 input[type="range"] { width: 100%; height: 10px; -webkit-appearance: none; background: #ffc107; border-radius: 5px; outline: none; }
        #sliderContainer10mo input[type="range"]::-webkit-slider-thumb, #sliderContainer11_12 input[type="range"]::-webkit-slider-thumb { -webkit-appearance: none; width: 30px; height: 30px; border-radius: 50%; background: #d39e00; cursor: pointer; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .valor-actual { text-align: center; font-size: 1.8em; font-weight: 800; color: #856404; margin-top: 8px; }
        
        .notificador-actual { display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 16px; border-radius: 16px; border: 1px solid #e8edf2; }
        .notificador-info { display: flex; align-items: center; gap: 14px; }
        .notificador-info i { font-size: 42px; color: #667eea; }
        .notificador-info strong { font-size: 1em; color: #1e293b; }
        .notificador-info span { color: #64748b; font-size: 0.9em; }
        .notificador-cargo { font-size: 0.8em; background: #e2e8f0; padding: 3px 10px; border-radius: 20px; display: inline-block; margin-top: 5px; }
        .btn-cambiar { background: #e2e8f0; border: none; padding: 10px 16px; border-radius: 12px; font-size: 0.9em; display: flex; align-items: center; gap: 6px; cursor: pointer; }
        
        .notificador-temporal { margin-top: 15px; }
        .temp-buttons { display: flex; gap: 10px; margin-top: 15px; }
        .btn-verificar { flex: 1; padding: 14px; background: #10b981; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; }
        .btn-cancelar { flex: 1; padding: 14px; background: #e2e8f0; color: #64748b; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; }
        
        .btn-submit { width: 100%; padding: 18px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border: none; border-radius: 16px; font-size: 1.2em; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; margin-top: 10px; }
        .btn-submit:disabled { background: #ccc; cursor: not-allowed; }
        
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); justify-content: center; align-items: flex-end; z-index: 1000; }
        .modal.show { display: flex; }
        .modal-content { background: white; border-radius: 32px 32px 0 0; padding: 28px 22px; width: 100%; max-width: 500px; animation: slideUp 0.25s ease; }
        @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
        .modal-content h2 { color: #1e3c72; margin-bottom: 20px; font-size: 1.4em; }
        .confirm-resumen { margin-bottom: 20px; }
        .confirm-section { margin-bottom: 18px; }
        .confirm-section h3 { color: #64748b; font-size: 0.9em; margin-bottom: 10px; }
        .confirm-tags { display: flex; flex-wrap: wrap; gap: 8px; }
        .confirm-tags span { background: #e2e8f0; padding: 6px 14px; border-radius: 20px; font-size: 0.85em; color: #1e293b; }
        .confirm-actividad { padding: 10px 14px; border-radius: 12px; margin-bottom: 8px; }
        .confirm-actividad.merito { background: #d1fae5; }
        .confirm-actividad.demerito { background: #fee2e2; }
        .modal-buttons { display: flex; gap: 12px; margin-top: 20px; }
        .modal-buttons button { flex: 1; padding: 15px; border-radius: 14px; font-weight: 600; font-size: 1em; cursor: pointer; border: none; }
        .modal-buttons .btn-cancelar { background: #e2e8f0; color: #64748b; }
        .modal-buttons .btn-confirmar { background: #10b981; color: white; }
        
        .success-modal { text-align: center; }
        .success-icon { font-size: 65px; color: #10b981; margin-bottom: 15px; }
        .btn-success { width: 100%; padding: 16px; background: #1e3c72; color: white; border: none; border-radius: 14px; font-size: 1.1em; font-weight: 600; cursor: pointer; }
        
        @media (max-width: 480px) {
            .header { padding: 14px 16px; }
            .main-content { padding: 16px 12px; }
            .form-section { padding: 18px 16px; }
            .form-row { flex-direction: column; gap: 10px; }
            .tipo-btn { padding: 12px; font-size: 0.95em; }
            .btn-submit { padding: 16px; font-size: 1.1em; }
            .modal-content { padding: 24px 18px; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-left">
            <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
            <h1 class="header-title">Notificar Actividad</h1>
        </div>
    </header>

    <main class="main-content">
        <form id="notificarForm" method="POST" action="notificar_procesar.php">
            
            <div class="form-section">
                <h2 class="section-title"><i class="fas fa-users"></i> Destinatarios</h2>
                <div class="destinatarios-list" id="destinatariosList">
                    <?php if (!empty($usuarios_escaneados)): ?>
                        <?php foreach ($usuarios_escaneados as $usuario): ?>
                        <div class="destinatario-tag" data-id="<?php echo htmlspecialchars($usuario['id'] ?? ''); ?>" data-nombre="<?php echo htmlspecialchars(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellidos'] ?? '')); ?>" data-ci="<?php echo htmlspecialchars($usuario['ci'] ?? ''); ?>" data-grado="<?php echo htmlspecialchars($usuario['grado'] ?? '10mo'); ?>">
                            <span><?php echo htmlspecialchars(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellidos'] ?? '')); ?> (<?php echo htmlspecialchars($usuario['ci'] ?? ''); ?>) - <?php echo htmlspecialchars($usuario['grado'] ?? '10mo'); ?></span>
                            <i class="fas fa-times-circle" onclick="this.parentElement.remove(); actualizarDestinatariosInput();"></i>
                        </div>
                        <?php endforeach; ?>
                        <div class="mensaje-exito" id="mensajeExito"><i class="fas fa-check-circle"></i> <?php echo count($usuarios_escaneados); ?> estudiante(s) cargado(s)!</div>
                    <?php else: ?>
                        <p class="empty-destinatarios" id="emptyDestinatarios">No hay destinatarios agregados</p>
                    <?php endif; ?>
                </div>
                
                <div class="search-box">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="buscarUsuario" placeholder="Buscar por CI, ID o nombre..." autocomplete="off">
                    </div>
                    <div class="search-results" id="searchResults"></div>
                </div>
                
                <button type="button" class="btn-qr-scanner" onclick="window.location.href='escaner_qr.php'">
                    <i class="fas fa-qrcode"></i> Escanear QR
                </button>
                <input type="hidden" name="destinatarios" id="destinatariosInput" value="<?php echo htmlspecialchars(json_encode($usuarios_escaneados)); ?>">
            </div>

            <div class="form-section">
                <h2 class="section-title"><i class="fas fa-list-check"></i> Actividades</h2>
                <div class="actividades-list" id="actividadesList"></div>
                <div class="actividad-buscador-container">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="buscarActividad" placeholder="Buscar merito o demerito..." autocomplete="off">
                    </div>
                    <div class="actividad-search-results" id="actividadSearchResults"></div>
                </div>
                <div class="tipo-selector">
                    <button type="button" class="tipo-btn active" data-tipo="merito"><i class="fas fa-trophy"></i> Merito</button>
                    <button type="button" class="tipo-btn" data-tipo="demerito"><i class="fas fa-exclamation-triangle"></i> Demerito</button>
                </div>
                <div class="form-group"><label>Categoria</label><select id="selectCategoria" class="form-control"><option value="">Todas</option></select></div>
                <div class="form-group"><label>Actividad</label><select id="selectActividad" class="form-control"><option value="">Selecciona</option></select></div>
                
                <div class="form-group" id="cantidadContainer">
                    <label id="cantidadLabel">Valor</label>
                    <input type="number" id="cantidad" class="form-control" min="1" value="1">
                </div>
                
                <div id="sliderContainer10mo">
                    <label><i class="fas fa-sliders-h"></i> Rango demeritos - <b>10mo</b></label>
                    <div class="rango-info">
                        <span id="sliderMin10mo">1</span>
                        <span id="sliderGrado10mo">Estudiantes: -</span>
                        <span id="sliderMax10mo">3</span>
                    </div>
                    <input type="range" id="sliderRango10mo" min="1" max="3" value="1">
                    <div class="valor-actual" id="sliderValor10mo">1</div>
                </div>
                
                <div id="sliderContainer11_12">
                    <label><i class="fas fa-sliders-h"></i> Rango demeritos - <b>11no/12mo</b></label>
                    <div class="rango-info">
                        <span id="sliderMin11_12">1</span>
                        <span id="sliderGrado11_12">Estudiantes: -</span>
                        <span id="sliderMax11_12">3</span>
                    </div>
                    <input type="range" id="sliderRango11_12" min="1" max="3" value="1">
                    <div class="valor-actual" id="sliderValor11_12">1</div>
                </div>
                
                <button type="button" class="btn-add-actividad" id="btnAgregarActividad"><i class="fas fa-plus"></i> Agregar</button>
                <input type="hidden" name="actividades" id="actividadesInput">
            </div>

            <div class="form-section">
                <h2 class="section-title"><i class="fas fa-calendar-clock"></i> Fecha y Hora</h2>
                <div class="form-row">
                    <div class="form-group"><label>Fecha</label><input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>"></div>
                    <div class="form-group"><label>Hora</label><input type="time" name="hora" id="hora" class="form-control" value="<?php echo date('H:i'); ?>"></div>
                </div>
            </div>

            <div class="form-section">
                <h2 class="section-title"><i class="fas fa-user-pen"></i> Quien Notifica</h2>
                <div class="notificador-actual" id="notificadorActual">
                    <div class="notificador-info">
                        <i class="fas fa-user-circle"></i>
                        <div>
                            <strong>Yo (Cuenta actual)</strong>
                            <span><?php echo htmlspecialchars($usuario_nombre . ' ' . $usuario_apellidos); ?></span>
                            <span class="notificador-cargo"><?php echo ucfirst($usuario_cargo); ?></span>
                        </div>
                    </div>
                    <button type="button" class="btn-cambiar" id="btnCambiarNotificador"><i class="fas fa-exchange-alt"></i> Cambiar</button>
                </div>
                <div class="notificador-temporal" id="notificadorTemporal" style="display:none;">
                    <div class="form-group"><label>Nombre</label><input type="text" id="tempNombre" class="form-control" placeholder="Ej: Juan Perez"></div>
                    <div class="form-group"><label>Contrasena</label><input type="password" id="tempPassword" class="form-control" placeholder="Contrasena"></div>
                    <div class="temp-buttons">
                        <button type="button" class="btn-verificar" id="btnVerificarTemp"><i class="fas fa-check"></i> Verificar</button>
                        <button type="button" class="btn-cancelar" id="btnCancelarTemp"><i class="fas fa-times"></i> Cancelar</button>
                    </div>
                </div>
                <input type="hidden" name="id_star" id="id_star" value="<?php echo $usuario_id; ?>">
                <input type="hidden" name="tipo_notificador" id="tipo_notificador" value="cuenta">
                <input type="hidden" name="cargo_notificador" id="cargo_notificador" value="<?php echo $usuario_cargo; ?>">
            </div>
            
            <div class="form-section">
                <h2 class="section-title"><i class="fas fa-pencil"></i> Observaciones</h2>
                <textarea name="observaciones" class="form-control" rows="3" placeholder="Notas adicionales..."></textarea>
            </div>

            <button type="submit" class="btn-submit" id="btnSubmit" disabled><i class="fas fa-paper-plane"></i> Notificar</button>
        </form>
    </main>

    <div class="modal" id="confirmModal">
        <div class="modal-content">
            <h2><i class="fas fa-clipboard-list"></i> Confirmar</h2>
            <div class="confirm-resumen" id="confirmResumen"></div>
            <div class="modal-buttons">
                <button type="button" class="btn-cancelar" id="btnCancelarConfirm">Cancelar</button>
                <button type="button" class="btn-confirmar" id="btnConfirmarEnvio">Confirmar</button>
            </div>
        </div>
    </div>

    <div class="modal" id="successModal">
        <div class="modal-content success-modal">
            <i class="fas fa-check-circle success-icon"></i>
            <h2>Notificacion Exitosa!</h2>
            <p>Las actividades han sido registradas.</p>
            <button type="button" class="btn-success" onclick="window.location.href='index.php'">Volver</button>
        </div>
    </div>
    <script>
    const catalogoMeritos = <?php echo json_encode($catalogo_meritos); ?>;
    const catalogoDemeritos = <?php echo json_encode($catalogo_demeritos); ?>;
    const usuarioActual = {
        id: <?php echo $usuario_id; ?>,
        nombre: '<?php echo $usuario_nombre . " " . $usuario_apellidos; ?>',
        cargo: '<?php echo $usuario_cargo; ?>'
    };
    
    let tipoActual = 'merito';
    let actividadSeleccionada = null;
    let actividadesAgregadas = [];
    
    // FUNCIONES GLOBALES
    function obtenerDestinatariosPorGrado() {
        const tags = document.querySelectorAll('.destinatario-tag');
        const grupos = { '10mo': [], '11no': [], '12mo': [] };
        tags.forEach(tag => {
            const grado = tag.dataset.grado || '10mo';
            if (grado === '10mo') {
                grupos['10mo'].push(tag.dataset.nombre);
            } else {
                grupos['11no'].push(tag.dataset.nombre + ' (' + grado + ')');
            }
        });
        return grupos;
    }
    
    function parsearRango(rangoStr) {
        if (!rangoStr) return [1, 1];
        const partes = rangoStr.toString().split('-');
        return [parseInt(partes[0]) || 1, parseInt(partes[1]) || parseInt(partes[0]) || 1];
    }
    
    function mostrarSlider(actividad) {
        const slider10mo = document.getElementById('sliderContainer10mo');
        const slider11_12 = document.getElementById('sliderContainer11_12');
        const cantidadContainer = document.getElementById('cantidadContainer');
        
        slider10mo.style.display = 'none';
        slider11_12.style.display = 'none';
        
        if (tipoActual !== 'demerito') {
            cantidadContainer.style.display = 'block';
            return;
        }
        
        const grupos = obtenerDestinatariosPorGrado();
        const hay10mo = grupos['10mo'].length > 0;
        const hay11_12 = grupos['11no'].length > 0;
        
        if (!hay10mo && !hay11_12) {
            cantidadContainer.style.display = 'block';
            return;
        }
        
        cantidadContainer.style.display = 'none';
        
        if (hay10mo && actividad.demeritos_10mo) {
            const [min, max] = parsearRango(actividad.demeritos_10mo);
            document.getElementById('sliderRango10mo').min = min;
            document.getElementById('sliderRango10mo').max = max;
            document.getElementById('sliderRango10mo').value = min;
            document.getElementById('sliderMin10mo').textContent = min;
            document.getElementById('sliderMax10mo').textContent = max;
            document.getElementById('sliderValor10mo').textContent = min;
            document.getElementById('sliderGrado10mo').textContent = 'Estudiantes: ' + grupos['10mo'].join(', ');
            slider10mo.style.display = 'block';
        }
        
        if (hay11_12 && actividad.demeritos_11_12) {
            const [min, max] = parsearRango(actividad.demeritos_11_12);
            document.getElementById('sliderRango11_12').min = min;
            document.getElementById('sliderRango11_12').max = max;
            document.getElementById('sliderRango11_12').value = min;
            document.getElementById('sliderMin11_12').textContent = min;
            document.getElementById('sliderMax11_12').textContent = max;
            document.getElementById('sliderValor11_12').textContent = min;
            document.getElementById('sliderGrado11_12').textContent = 'Estudiantes: ' + grupos['11no'].join(', ');
            slider11_12.style.display = 'block';
        }
        
        if (hay10mo) {
            document.getElementById('cantidad').value = document.getElementById('sliderRango10mo').value;
        } else if (hay11_12) {
            document.getElementById('cantidad').value = document.getElementById('sliderRango11_12').value;
        }
    }
    
    function verificarFormulario() {
        const btn = document.getElementById('btnSubmit');
        const destValue = document.getElementById('destinatariosInput').value || '[]';
        const dest = JSON.parse(destValue);
        btn.disabled = !(dest.length > 0 && actividadesAgregadas.length > 0);
    }
    
    function actualizarDestinatariosInput() {
        const tags = document.querySelectorAll('.destinatario-tag');
        const destinatarios = [];
        tags.forEach(tag => {
            if (tag.dataset.id) {
                destinatarios.push({ id: tag.dataset.id, nombre: tag.dataset.nombre, ci: tag.dataset.ci, grado: tag.dataset.grado || '10mo' });
            }
        });
        document.getElementById('destinatariosInput').value = JSON.stringify(destinatarios);
        verificarFormulario();
        
        const emptyMsg = document.querySelector('.empty-destinatarios');
        if (emptyMsg) emptyMsg.style.display = destinatarios.length > 0 ? 'none' : 'block';
        
        // Si no hay destinatarios, resetear sliders
        if (destinatarios.length === 0) {
            document.getElementById('sliderContainer10mo').style.display = 'none';
            document.getElementById('sliderContainer11_12').style.display = 'none';
            document.getElementById('cantidadContainer').style.display = 'block';
            actividadSeleccionada = null;
            document.getElementById('selectActividad').value = '';
            document.getElementById('cantidad').value = 1;
        }
        
        if (actividadSeleccionada && tipoActual === 'demerito' && destinatarios.length > 0) {
            mostrarSlider(actividadSeleccionada);
        }
        
        if (actividadesAgregadas.length > 0) {
            renderActividadesList();
        }
    }
    
    function agregarDestinatario(id, nombre, ci, grado) {
        const existentes = document.querySelectorAll('.destinatario-tag');
        for (let tag of existentes) {
            if (tag.dataset.id === String(id)) { alert('Ya esta en la lista'); return; }
        }
        const emptyMsg = document.querySelector('.empty-destinatarios');
        if (emptyMsg) emptyMsg.remove();
        
        const tag = document.createElement('div');
        tag.className = 'destinatario-tag';
        tag.dataset.id = id;
        tag.dataset.nombre = nombre;
        tag.dataset.ci = ci;
        tag.dataset.grado = grado || '10mo';
        tag.innerHTML = '<span>' + nombre + ' (' + ci + ') - ' + (grado || '10mo') + '</span><i class="fas fa-times-circle" onclick="this.parentElement.remove(); actualizarDestinatariosInput();"></i>';
        document.getElementById('destinatariosList').appendChild(tag);
        actualizarDestinatariosInput();
        document.getElementById('buscarUsuario').value = '';
        document.getElementById('searchResults').classList.remove('show');
    }
    
    // CORREGIDO: Función global para eliminar actividad
    window.eliminarActividad = function(index) {
        actividadesAgregadas.splice(index, 1);
        renderActividadesList();
        document.getElementById('actividadesInput').value = JSON.stringify(actividadesAgregadas);
        verificarFormulario();
    };
    
    function buscarEstudiantes(query, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'ajax_buscar.php?q=' + encodeURIComponent(query), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                try { callback(JSON.parse(xhr.responseText), null); } catch (e) { callback(null, 'Error'); }
            } else { callback(null, 'Error ' + xhr.status); }
        };
        xhr.onerror = function() { callback(null, 'Error de conexion'); };
        xhr.timeout = 10000;
        xhr.send();
    }
    document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('sliderRango10mo').oninput = function() {
        document.getElementById('sliderValor10mo').textContent = this.value;
        document.getElementById('cantidad').value = this.value;
    };
    document.getElementById('sliderRango11_12').oninput = function() {
        document.getElementById('sliderValor11_12').textContent = this.value;
        document.getElementById('cantidad').value = this.value;
    };
    
    const buscarUsuario = document.getElementById('buscarUsuario');
    const searchResults = document.getElementById('searchResults');
    
    if (buscarUsuario && searchResults) {
        let timeout;
        buscarUsuario.oninput = function() {
            clearTimeout(timeout);
            const query = this.value.trim();
            if (query.length < 2) { searchResults.classList.remove('show'); searchResults.innerHTML = ''; return; }
            searchResults.innerHTML = '<p class="search-empty">Buscando...</p>';
            searchResults.classList.add('show');
            
            timeout = setTimeout(function() {
                buscarEstudiantes(query, function(data, error) {
                    if (error) { searchResults.innerHTML = '<p class="search-empty">' + error + '</p>'; return; }
                    if (!data || !data.length) {
                        searchResults.innerHTML = '<p class="search-empty">No se encontraron estudiantes</p>';
                    } else {
                        let html = '';
                        data.forEach(function(u) {
                            html += '<div class="search-result-item" onclick="agregarDestinatario(\'' + u.id + '\',\'' + u.nombre + ' ' + u.apellidos + '\',\'' + u.ci + '\',\'' + u.grado + '\')">';
                            html += '<div class="search-result-info"><span class="search-result-name">' + u.nombre + ' ' + u.apellidos + '</span><span class="search-result-details">CI: ' + u.ci + ' | Grado: ' + u.grado + '</span></div>';
                            html += '<i class="fas fa-plus-circle" style="color:#10b981;font-size:1.3em;"></i></div>';
                        });
                        searchResults.innerHTML = html;
                    }
                });
            }, 300);
        };
        document.onclick = function(e) {
            if (!buscarUsuario.contains(e.target) && !searchResults.contains(e.target)) searchResults.classList.remove('show');
        };
    }
    
    document.getElementById('btnCambiarNotificador').onclick = function() {
        document.getElementById('notificadorActual').style.display = 'none';
        document.getElementById('notificadorTemporal').style.display = 'block';
    };
    document.getElementById('btnCancelarTemp').onclick = function() {
        document.getElementById('notificadorTemporal').style.display = 'none';
        document.getElementById('notificadorActual').style.display = 'flex';
        document.getElementById('tempNombre').value = '';
        document.getElementById('tempPassword').value = '';
        document.getElementById('id_star').value = usuarioActual.id;
        document.getElementById('tipo_notificador').value = 'cuenta';
    };
    document.getElementById('btnVerificarTemp').onclick = function() {
        const nombre = document.getElementById('tempNombre').value.trim();
        const password = document.getElementById('tempPassword').value;
        if (!nombre || !password) { alert('Completa todos los campos'); return; }
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'verificar_notificador.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    document.getElementById('id_star').value = data.id;
                    document.getElementById('tipo_notificador').value = 'temporal';
                    document.getElementById('notificadorTemporal').style.display = 'none';
                    document.getElementById('notificadorActual').style.display = 'flex';
                    document.querySelector('#notificadorActual .notificador-info span').textContent = nombre;
                    document.querySelector('#notificadorActual .notificador-cargo').textContent = data.cargo || 'Temporal';
                    document.querySelector('#notificadorActual strong').textContent = 'Cuenta Temporal';
                    document.getElementById('tempNombre').value = '';
                    document.getElementById('tempPassword').value = '';
                    alert('Verificado correctamente');
                } else { alert('Credenciales incorrectas'); }
            }
        };
        xhr.send(JSON.stringify({ nombre: nombre, password: password }));
    };
    
    const selectCategoria = document.getElementById('selectCategoria');
    const selectActividad = document.getElementById('selectActividad');
    const cantidadInput = document.getElementById('cantidad');
    
    function cargarCategorias() {
        const catalogo = tipoActual === 'merito' ? catalogoMeritos : catalogoDemeritos;
        const categorias = [...new Set(catalogo.map(function(i) { return i.categoria; }))];
        selectCategoria.innerHTML = '<option value="">Todas</option>';
        categorias.sort().forEach(function(cat) {
            const o = document.createElement('option'); o.value = cat; o.textContent = cat;
            selectCategoria.appendChild(o);
        });
    }
    
    function cargarActividades(categoria) {
        const catalogo = tipoActual === 'merito' ? catalogoMeritos : catalogoDemeritos;
        const acts = categoria ? catalogo.filter(function(i) { return i.categoria === categoria; }) : catalogo;
        selectActividad.innerHTML = '<option value="">Selecciona</option>';
        acts.forEach(function(act) {
            const o = document.createElement('option');
            o.value = JSON.stringify(act);
            if (tipoActual === 'demerito') {
                o.textContent = act.falta + ' (10mo: ' + act.demeritos_10mo + ' | 11/12: ' + act.demeritos_11_12 + ')';
            } else {
                o.textContent = act.causa + ' (' + act.meritos + ')';
            }
            selectActividad.appendChild(o);
        });
    }
    
    document.querySelectorAll('.tipo-btn').forEach(function(btn) {
        btn.onclick = function() {
            document.querySelectorAll('.tipo-btn').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            tipoActual = this.dataset.tipo;
            
            document.getElementById('sliderContainer10mo').style.display = 'none';
            document.getElementById('sliderContainer11_12').style.display = 'none';
            document.getElementById('cantidadContainer').style.display = 'block';
            cantidadInput.value = 1;
            actividadSeleccionada = null;
            
            cargarCategorias();
            cargarActividades('');
            document.getElementById('buscarActividad').value = '';
            document.getElementById('actividadSearchResults').style.display = 'none';
        };
    });
    
    selectCategoria.onchange = function() {
        cargarActividades(this.value);
        document.getElementById('buscarActividad').value = '';
        document.getElementById('actividadSearchResults').style.display = 'none';
    };
    
    selectActividad.onchange = function() {
        if (this.value) {
            try {
                actividadSeleccionada = JSON.parse(this.value);
                if (tipoActual === 'merito') {
                    document.getElementById('sliderContainer10mo').style.display = 'none';
                    document.getElementById('sliderContainer11_12').style.display = 'none';
                    document.getElementById('cantidadContainer').style.display = 'block';
                    cantidadInput.value = actividadSeleccionada.meritos || 1;
                } else {
                    mostrarSlider(actividadSeleccionada);
                }
            } catch (e) { alert('Error al seleccionar actividad'); }
        }
    };
                const buscarActividad = document.getElementById('buscarActividad');
            const actividadSearchResults = document.getElementById('actividadSearchResults');
            
            buscarActividad.oninput = function() {
                const query = this.value.trim().toLowerCase();
                if (query.length < 1) { actividadSearchResults.style.display = 'none'; actividadSearchResults.innerHTML = ''; return; }
                
                const catalogo = tipoActual === 'merito' ? catalogoMeritos : catalogoDemeritos;
                const categoriaFiltro = selectCategoria.value || '';
                let resultados = catalogo.filter(function(item) {
                    const texto = tipoActual === 'merito' ? (item.causa + ' ' + item.categoria).toLowerCase() : (item.falta + ' ' + item.categoria).toLowerCase();
                    return texto.includes(query) && (!categoriaFiltro || item.categoria === categoriaFiltro);
                });
                
                if (resultados.length === 0) {
                    actividadSearchResults.innerHTML = '<p class="search-empty">No se encontraron actividades</p>';
                } else {
                    actividadSearchResults.innerHTML = resultados.map(function(item) {
                        const nombre = tipoActual === 'merito' ? item.causa : item.falta;
                        const valor = tipoActual === 'merito' ? item.meritos : item.demeritos_10mo + ' / ' + item.demeritos_11_12;
                        const bg = tipoActual === 'merito' ? '#d1fae5' : '#fee2e2';
                        const tc = tipoActual === 'merito' ? '#065f46' : '#991b1b';
                        const istr = JSON.stringify(item).replace(/"/g, '&quot;');
                        return '<div class="actividad-search-item" onclick="window.seleccionarDesdeBusqueda(' + istr + ')">' +
                            '<div class="actividad-search-info"><div class="actividad-search-nombre">' + nombre + '</div><div class="actividad-search-categoria">' + item.categoria + '</div></div>' +
                            '<div class="actividad-search-valor" style="background:' + bg + ';color:' + tc + ';">' + (tipoActual === 'merito' ? '+' + valor : valor) + '</div></div>';
                    }).join('');
                }
                actividadSearchResults.style.display = 'block';
            };
            
            window.seleccionarDesdeBusqueda = function(itemData) {
                actividadSeleccionada = itemData;
                if (itemData.categoria) { selectCategoria.value = itemData.categoria; cargarActividades(itemData.categoria); }
                setTimeout(function() { selectActividad.value = JSON.stringify(itemData); }, 50);
                
                if (tipoActual === 'merito') {
                    document.getElementById('sliderContainer10mo').style.display = 'none';
                    document.getElementById('sliderContainer11_12').style.display = 'none';
                    document.getElementById('cantidadContainer').style.display = 'block';
                    cantidadInput.value = itemData.meritos || 1;
                } else {
                    mostrarSlider(itemData);
                }
                actividadSearchResults.style.display = 'none';
                buscarActividad.value = '';
            };
            
            document.getElementById('btnAgregarActividad').onclick = function() {
                if (!actividadSeleccionada && selectActividad.value) {
                    try { actividadSeleccionada = JSON.parse(selectActividad.value); } catch (e) {}
                }
                if (!actividadSeleccionada) { alert('Selecciona una actividad'); return; }
                
                const cantidad = parseInt(cantidadInput.value) || 1;
                let valor10mo = null;
                let valor11_12 = null;
                
                if (tipoActual === 'demerito') {
                    if (document.getElementById('sliderContainer10mo').style.display === 'block') {
                        valor10mo = parseInt(document.getElementById('sliderRango10mo').value);
                    }
                    if (document.getElementById('sliderContainer11_12').style.display === 'block') {
                        valor11_12 = parseInt(document.getElementById('sliderRango11_12').value);
                    }
                }
                
                actividadesAgregadas.push({
                    tipo: tipoActual,
                    categoria: selectCategoria.value || actividadSeleccionada.categoria,
                    actividad_id: actividadSeleccionada.id,
                    nombre: tipoActual === 'merito' ? actividadSeleccionada.causa : actividadSeleccionada.falta,
                    cantidad: cantidad,
                    valor10mo: valor10mo,
                    valor11_12: valor11_12
                });
                
                renderActividadesList();
                document.getElementById('actividadesInput').value = JSON.stringify(actividadesAgregadas);
                verificarFormulario();
                
                actividadSeleccionada = null;
                selectActividad.value = '';
                cantidadInput.value = 1;
                document.getElementById('sliderContainer10mo').style.display = 'none';
                document.getElementById('sliderContainer11_12').style.display = 'none';
                document.getElementById('cantidadContainer').style.display = 'block';
            };
            
            function renderActividadesList() {
                const lista = document.getElementById('actividadesList');
                if (!actividadesAgregadas.length) { lista.innerHTML = '<p class="empty-actividades">No hay actividades agregadas</p>'; return; }
                
                const grupos = obtenerDestinatariosPorGrado();
                const hay10mo = grupos['10mo'].length > 0;
                const hay11_12 = grupos['11no'].length > 0;
                const hayAmbos = hay10mo && hay11_12;
                
                lista.innerHTML = actividadesAgregadas.map(function(act, i) {
                    const bg = act.tipo === 'merito' ? '#d1fae5' : '#fee2e2';
                    const border = act.tipo === 'merito' ? '#10b981' : '#ef4444';
                    const color = act.tipo === 'merito' ? '#065f46' : '#991b1b';
                    
                    let valorHTML = '';
                    if (act.tipo === 'merito') {
                        valorHTML = '<span style="color:' + color + ';font-weight:700;font-size:1.1em;">+' + act.cantidad + '</span>';
                    } else {
                        if (hayAmbos && act.valor10mo !== null && act.valor11_12 !== null) {
                            valorHTML = '<div class="actividad-tag-valores">' +
                                '<span style="color:#b45309;font-weight:600;">10mo: -' + act.valor10mo + '</span>' +
                                '<span style="color:#b45309;font-weight:600;">11/12: -' + act.valor11_12 + '</span></div>';
                        } else if (hay10mo && act.valor10mo !== null) {
                            valorHTML = '<span style="color:' + color + ';font-weight:700;font-size:1.1em;">-' + act.valor10mo + '</span>';
                        } else if (hay11_12 && act.valor11_12 !== null) {
                            valorHTML = '<span style="color:' + color + ';font-weight:700;font-size:1.1em;">-' + act.valor11_12 + '</span>';
                        } else {
                            valorHTML = '<span style="color:' + color + ';font-weight:700;font-size:1.1em;">-' + act.cantidad + '</span>';
                        }
                    }
                    
                    return '<div class="actividad-tag" style="background:' + bg + ';border-left:4px solid ' + border + ';">' +
                        '<div><div class="actividad-tag-nombre">' + act.nombre + '</div><div class="actividad-tag-categoria">' + act.categoria + '</div></div>' +
                        '<div class="actividad-tag-actions">' + valorHTML +
                        '<i class="fas fa-trash-alt" onclick="window.eliminarActividad(' + i + ')" style="cursor:pointer;color:#ef4444;font-size:1.1em;"></i></div></div>';
                }).join('');
            }
            
            document.getElementById('notificarForm').onsubmit = function(e) {
                e.preventDefault();
                const confirmModal = document.getElementById('confirmModal');
                const confirmResumen = document.getElementById('confirmResumen');
                const destinatarios = JSON.parse(document.getElementById('destinatariosInput').value || '[]');
                const fecha = document.getElementById('fecha').value;
                const hora = document.getElementById('hora').value;
                const obs = document.querySelector('textarea[name="observaciones"]').value || 'Ninguna';
                const nn = document.querySelector('#notificadorActual .notificador-info span').textContent;
                const nc = document.querySelector('#notificadorActual .notificador-cargo').textContent;
                
                let h = '<div class="confirm-section"><h3>Destinatarios (' + destinatarios.length + ')</h3><div class="confirm-tags">';
                destinatarios.forEach(function(d) { h += '<span>' + d.nombre + ' (' + (d.grado || '10mo') + ')</span>'; });
                h += '</div></div><div class="confirm-section"><h3>Actividades (' + actividadesAgregadas.length + ')</h3>';
                
                const grupos = obtenerDestinatariosPorGrado();
                const hayAmbos = grupos['10mo'].length > 0 && grupos['11no'].length > 0;
                
                actividadesAgregadas.forEach(function(a) {
                    let valorTexto = '';
                    if (a.tipo === 'merito') {
                        valorTexto = '+' + a.cantidad;
                    } else {
                        if (hayAmbos && a.valor10mo !== null && a.valor11_12 !== null) {
                            valorTexto = '10mo: -' + a.valor10mo + ' | 11/12: -' + a.valor11_12;
                        } else {
                            valorTexto = '-' + a.cantidad;
                        }
                    }
                    h += '<div class="confirm-actividad ' + a.tipo + '"><strong>' + a.nombre + '</strong><br><small>' + a.categoria + ' | ' + valorTexto + '</small></div>';
                });
                h += '</div><div class="confirm-section"><h3>Fecha y Hora</h3><p>' + fecha + ' | ' + hora + '</p></div>';
                h += '<div class="confirm-section"><h3>Notificado por</h3><p>' + nn + ' (' + nc + ')</p></div>';
                h += '<div class="confirm-section"><h3>Observaciones</h3><p>' + obs + '</p></div>';
                
                confirmResumen.innerHTML = h;
                confirmModal.classList.add('show');
                
                document.getElementById('btnCancelarConfirm').onclick = function() { confirmModal.classList.remove('show'); };
                document.getElementById('btnConfirmarEnvio').onclick = function() {
                    confirmModal.classList.remove('show');
                    document.getElementById('actividadesInput').value = JSON.stringify(actividadesAgregadas);
                    
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'notificar_procesar.php', true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const data = JSON.parse(xhr.responseText);
                            if (data.success) {
                                document.getElementById('successModal').classList.add('show');
                                actividadesAgregadas = [];
                                renderActividadesList();
                                document.getElementById('actividadesInput').value = '[]';
                                document.querySelectorAll('.destinatario-tag').forEach(function(t) { t.remove(); });
                                actualizarDestinatariosInput();
                                if (document.getElementById('tipo_notificador').value === 'temporal') {
                                    document.getElementById('id_star').value = usuarioActual.id;
                                    document.getElementById('tipo_notificador').value = 'cuenta';
                                    document.querySelector('#notificadorActual .notificador-info span').textContent = usuarioActual.nombre;
                                    document.querySelector('#notificadorActual .notificador-cargo').textContent = usuarioActual.cargo;
                                    document.querySelector('#notificadorActual strong').textContent = 'Yo (Cuenta actual)';
                                }
                            } else { alert('Error: ' + (data.message || 'No se pudo procesar')); }
                        }
                    };
                    xhr.send(new FormData(document.getElementById('notificarForm')));
                };
                confirmModal.onclick = function(e) { if (e.target === confirmModal) confirmModal.classList.remove('show'); };
            };
            
            cargarCategorias();
            cargarActividades('');
            document.getElementById('actividadesInput').value = '[]';
            actualizarDestinatariosInput();
            
            const msgExito = document.getElementById('mensajeExito');
            if (msgExito) { setTimeout(function() { msgExito.style.opacity = '0'; setTimeout(function() { msgExito.remove(); }, 500); }, 3000); }
        });
    </script>
</body>
</html>