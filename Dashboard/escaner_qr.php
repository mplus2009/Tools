<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header('Location: ../login.php');
    exit();
}

$usuario_actual_id = $_SESSION['usuario_id'] ?? 0;
$usuario_actual_ci = $_SESSION['usuario_ci'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#1e3c72">
    <title>Escanear QR - Sistema Escolar</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #000; min-height: 100vh; }
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 16px 20px; display: flex; align-items: center;
            position: sticky; top: 0; z-index: 100;
        }
        .back-btn {
            background: rgba(255,255,255,0.15); border: none;
            width: 46px; height: 46px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 22px; text-decoration: none;
            margin-right: 16px; cursor: pointer;
        }
        .header-title { color: white; font-size: 1.4em; font-weight: 600; flex: 1; }
        .header-right { display: flex; align-items: center; gap: 10px; }
        .counter-badge {
            background: #10b981; color: white; padding: 6px 12px;
            border-radius: 20px; font-size: 0.9em; font-weight: 600;
            display: flex; align-items: center; gap: 5px;
        }
        .scanner-container {
            position: relative; width: 100%; height: calc(100vh - 78px);
            background: #000; overflow: hidden;
        }
        #qrVideo { 
            width: 100% !important; height: 100% !important; 
            object-fit: cover !important; display: block !important;
            position: absolute; top: 0; left: 0;
        }
        .scanner-overlay {
            position: absolute; top: 0; left: 0;
            width: 100%; height: 100%; pointer-events: none; z-index: 10;
        }
        .scanner-frame {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 250px; height: 250px;
            border: 3px solid #10b981; border-radius: 20px;
            box-shadow: 0 0 0 2000px rgba(0,0,0,0.5);
        }
        .scanner-corner { position: absolute; width: 25px; height: 25px; border-color: white; border-style: solid; }
        .corner-tl { top: -3px; left: -3px; border-width: 4px 0 0 4px; border-radius: 8px 0 0 0; }
        .corner-tr { top: -3px; right: -3px; border-width: 4px 4px 0 0; border-radius: 0 8px 0 0; }
        .corner-bl { bottom: -3px; left: -3px; border-width: 0 0 4px 4px; border-radius: 0 0 0 8px; }
        .corner-br { bottom: -3px; right: -3px; border-width: 0 4px 4px 0; border-radius: 0 0 8px 0; }
        .scanner-text {
            position: absolute; bottom: 70px; left: 0; width: 100%;
            text-align: center; color: white; font-size: 1em; pointer-events: none;
        }
        .scanner-text span { background: rgba(0,0,0,0.6); padding: 8px 20px; border-radius: 40px; }
        .scanner-actions {
            position: absolute; bottom: 20px; right: 20px;
            display: flex; gap: 12px; z-index: 20;
        }
        .scanner-btn {
            background: rgba(255,255,255,0.9); border: none;
            width: 50px; height: 50px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #1e3c72; cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .scanner-btn.torch { background: #fbbf24; color: #000; }
        .scanner-btn.torch.active { background: #f59e0b; color: white; }
        
        .camera-error {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
            background: white; padding: 30px; border-radius: 24px;
            text-align: center; max-width: 320px; width: 90%; z-index: 100;
            display: none;
        }
        .camera-error.show { display: block; }
        .camera-error i { font-size: 50px; color: #ef4444; margin-bottom: 15px; }
        .camera-error h3 { color: #1e293b; margin-bottom: 10px; }
        .camera-error p { color: #64748b; margin-bottom: 20px; font-size: 0.9em; }
        .camera-error button {
            background: #1e3c72; color: white; border: none;
            padding: 14px 25px; border-radius: 14px;
            font-size: 1.1em; font-weight: 600; cursor: pointer; width: 100%;
        }
        
        .scanned-list {
            position: absolute; bottom: 0; left: 0; width: 100%;
            background: white; border-radius: 24px 24px 0 0;
            padding: 16px; max-height: 250px; overflow-y: auto;
            z-index: 20; transform: translateY(100%);
            transition: transform 0.3s ease;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
        }
        .scanned-list.show { transform: translateY(0); }
        .scanned-list h3 { color: #1e3c72; font-size: 1.1em; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .scanned-items { display: flex; flex-wrap: wrap; gap: 8px; }
        .scanned-tag {
            background: #e8f0fe; color: #1e3c72;
            padding: 8px 12px; border-radius: 20px;
            font-size: 0.85em; display: flex; align-items: center; gap: 8px;
        }
        .scanned-tag i { cursor: pointer; color: #ef4444; font-size: 1.1em; }
        .btn-finalizar {
            width: 100%; padding: 14px; background: #10b981;
            color: white; border: none; border-radius: 14px;
            font-size: 1.1em; font-weight: 600; margin-top: 12px; cursor: pointer;
        }
        
        .result-modal {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.8); backdrop-filter: blur(6px);
            justify-content: center; align-items: flex-end; z-index: 200;
        }
        .result-modal.show { display: flex; }
        .result-content {
            background: white; border-radius: 36px 36px 0 0;
            padding: 32px 24px; width: 100%; max-width: 480px;
            animation: slideUp 0.25s ease;
        }
        @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
        .result-icon { font-size: 60px; color: #10b981; text-align: center; margin-bottom: 15px; }
        .result-icon.error { color: #ef4444; }
        .result-content h2 { color: #1e3c72; margin-bottom: 20px; text-align: center; font-size: 1.5em; }
        .result-info { background: #f8fafc; padding: 20px; border-radius: 20px; margin-bottom: 25px; }
        .result-info-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e2e8f0; }
        .result-info-item:last-child { border-bottom: none; }
        .result-info-label { color: #64748b; font-weight: 500; font-size: 1em; }
        .result-info-value { color: #1e293b; font-weight: 600; font-size: 1em; }
        .result-buttons { display: flex; gap: 12px; }
        .btn-agregar {
            flex: 1; padding: 18px; background: #10b981;
            color: white; border: none; border-radius: 16px;
            font-size: 1.1em; font-weight: 600; cursor: pointer;
        }
        .btn-escanear-otro {
            flex: 1; padding: 18px; background: #e2e8f0;
            color: #64748b; border: none; border-radius: 16px;
            font-size: 1.1em; font-weight: 600; cursor: pointer;
        }
        .loading {
            display: none; position: absolute; top: 50%; left: 50%;
            transform: translate(-50%,-50%);
            color: white; text-align: center; z-index: 50;
        }
        .loading.show { display: block; }
        .spinner {
            width: 40px; height: 40px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: white; border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 12px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <header class="header">
        <a href="notificar.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
        <h1 class="header-title">Escanear QR</h1>
        <div class="header-right">
            <div class="counter-badge" id="counterBadge">
                <i class="fas fa-users"></i> <span id="scannedCount">0</span>
            </div>
        </div>
    </header>

    <div class="scanner-container" id="scannerContainer">
        <video id="qrVideo" playsinline autoplay muted></video>
        
        <div class="scanner-overlay">
            <div class="scanner-frame">
                <div class="scanner-corner corner-tl"></div>
                <div class="scanner-corner corner-tr"></div>
                <div class="scanner-corner corner-bl"></div>
                <div class="scanner-corner corner-br"></div>
            </div>
            <div class="scanner-text"><span>Coloca el QR dentro del recuadro</span></div>
        </div>

        <div class="scanner-actions">
            <button class="scanner-btn" id="switchCameraBtn" style="display:none;"><i class="fas fa-camera-rotate"></i></button>
            <button class="scanner-btn torch" id="torchBtn" style="display:none;"><i class="fas fa-flashlight"></i></button>
        </div>

        <div class="camera-error" id="cameraError">
            <i class="fas fa-camera-slash"></i>
            <h3>No se pudo acceder a la cámara</h3>
            <p>Verifica los permisos de cámara en la configuración de la app.</p>
            <button onclick="reintentarCamara()"><i class="fas fa-redo"></i> Reintentar</button>
        </div>

        <div class="scanned-list" id="scannedList">
            <h3><i class="fas fa-user-check"></i> Estudiantes escaneados (<span id="listCount">0</span>)</h3>
            <div class="scanned-items" id="scannedItems"></div>
            <button class="btn-finalizar" id="btnFinalizar"><i class="fas fa-check-circle"></i> Finalizar y Continuar</button>
        </div>

        <div class="loading" id="loading"><div class="spinner"></div><p>Procesando...</p></div>
    </div>

    <div class="result-modal" id="resultModal">
        <div class="result-content">
            <div class="result-icon" id="resultIcon"><i class="fas fa-check-circle"></i></div>
            <h2 id="resultTitle">Usuario Encontrado!</h2>
            <div class="result-info" id="resultInfo"></div>
            <div class="result-buttons">
                <button class="btn-agregar" id="btnAgregarYSeguir"><i class="fas fa-plus-circle"></i> Agregar y Seguir</button>
                <button class="btn-escanear-otro" id="btnSoloAgregar"><i class="fas fa-check"></i> Solo Agregar</button>
            </div>
        </div>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
    const urlParams = new URLSearchParams(window.location.search);
    const mode = urlParams.get('mode') || 'destinatario';
    
    const usuarioActualId = <?php echo json_encode($usuario_actual_id); ?>;
    const usuarioActualCI = <?php echo json_encode($usuario_actual_ci); ?>;
    
    let stream = null;
    let scanning = true;
    let currentFacingMode = 'environment';
    let torchEnabled = false;
    let usuarioEncontrado = null;
    let scannedUsers = [];
    
    const qrVideo = document.getElementById('qrVideo');
    const cameraError = document.getElementById('cameraError');
    const switchCameraBtn = document.getElementById('switchCameraBtn');
    const torchBtn = document.getElementById('torchBtn');
    const resultModal = document.getElementById('resultModal');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultInfo = document.getElementById('resultInfo');
    const btnAgregarYSeguir = document.getElementById('btnAgregarYSeguir');
    const btnSoloAgregar = document.getElementById('btnSoloAgregar');
    const loading = document.getElementById('loading');
    const scannedList = document.getElementById('scannedList');
    const scannedItems = document.getElementById('scannedItems');
    const scannedCount = document.getElementById('scannedCount');
    const listCount = document.getElementById('listCount');
    const btnFinalizar = document.getElementById('btnFinalizar');

    // ============================================
    // REINTENTAR CÁMARA (BOTÓN)
    // ============================================
    function reintentarCamara() {
        cameraError.classList.remove('show');
        stopCamera();
        setTimeout(startCamera, 500);
    }

    // ============================================
    // DETENER CÁMARA (IMPORTANTE PARA WEBVIEW)
    // ============================================
    function stopCamera() {
        scanning = false;
        if (stream) {
            stream.getTracks().forEach(function(track) {
                track.stop();
            });
            stream = null;
        }
        if (qrVideo) {
            qrVideo.srcObject = null;
        }
    }

    // ============================================
    // INICIAR CÁMARA (COMPATIBLE CON WEBVIEW)
    // ============================================
    async function startCamera() {
        try {
            // Verificar si el navegador soporta getUserMedia
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('getUserMedia no soportado');
            }
            
            // Intentar con cámara trasera primero
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: { exact: 'environment' },
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            });
            
            qrVideo.srcObject = stream;
            qrVideo.setAttribute('playsinline', '');
            qrVideo.setAttribute('autoplay', '');
            qrVideo.setAttribute('muted', '');
            
            await qrVideo.play();
            
            cameraError.classList.remove('show');
            scanning = true;
            requestAnimationFrame(scanLoop);
            
            // Verificar capacidades
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(function(d) { return d.kind === 'videoinput'; });
            if (videoDevices.length > 1) switchCameraBtn.style.display = 'flex';
            
            const track = stream.getVideoTracks()[0];
            if (track && track.getCapabilities && track.getCapabilities().torch) {
                torchBtn.style.display = 'flex';
            }
            
        } catch (err) {
            console.log('Error con cámara trasera, intentando cualquier cámara:', err.message);
            
            // Intentar sin 'exact'
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                });
                
                qrVideo.srcObject = stream;
                await qrVideo.play();
                
                cameraError.classList.remove('show');
                scanning = true;
                requestAnimationFrame(scanLoop);
                
            } catch (err2) {
                console.log('Error con cualquier cámara:', err2.message);
                
                // Último intento: cualquier cámara disponible
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { width: { ideal: 1280 }, height: { ideal: 720 } }
                    });
                    
                    qrVideo.srcObject = stream;
                    await qrVideo.play();
                    
                    cameraError.classList.remove('show');
                    scanning = true;
                    requestAnimationFrame(scanLoop);
                    
                } catch (err3) {
                    console.error('No se pudo acceder a ninguna cámara:', err3.message);
                    cameraError.classList.add('show');
                }
            }
        }
    }

    // ============================================
    // CAMBIAR CÁMARA (FRONTAL/TRASERA)
    // ============================================
    async function switchCamera() {
        stopCamera();
        currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
        torchEnabled = false;
        torchBtn.classList.remove('active');
        await startCamera();
    }

    // ============================================
    // LINTERNA
    // ============================================
    async function toggleTorch() {
        if (stream) {
            const track = stream.getVideoTracks()[0];
            if (track && track.getCapabilities && track.getCapabilities().torch) {
                try {
                    torchEnabled = !torchEnabled;
                    await track.applyConstraints({ advanced: [{ torch: torchEnabled }] });
                    torchBtn.classList.toggle('active', torchEnabled);
                } catch (e) {}
            }
        }
    }

    // ============================================
    // ESCANEO QR
    // ============================================
    function scanLoop() {
        if (!scanning || !qrVideo || !stream) return;
        
        if (qrVideo.readyState >= qrVideo.HAVE_ENOUGH_DATA) {
            try {
                const canvas = document.createElement('canvas');
                canvas.width = qrVideo.videoWidth;
                canvas.height = qrVideo.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(qrVideo, 0, 0, canvas.width, canvas.height);
                
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: 'dontInvert'
                });
                
                if (code) {
                    scanning = false;
                    loading.classList.add('show');
                    
                    try {
                        let qrText = code.data;
                        let usuario = null;
                        
                        // Intentar múltiples formas de decodificar
                        try { usuario = JSON.parse(atob(qrText)); } catch (e) {
                            try { usuario = JSON.parse(atob(qrText.replace(/-/g, '+').replace(/_/g, '/'))); } catch (e2) {
                                try { usuario = JSON.parse(qrText); } catch (e3) {
                                    try { usuario = JSON.parse(decodeURIComponent(escape(atob(qrText)))); } catch (e4) {
                                        throw new Error('Formato no reconocido');
                                    }
                                }
                            }
                        }
                        
                        if (usuario && (usuario.id || (usuario.nombre && usuario.apellidos && usuario.ci))) {
                            if (!usuario.cargo) usuario.cargo = 'estudiante';
                            mostrarResultado(usuario);
                        } else {
                            throw new Error('Datos incompletos');
                        }
                    } catch (e) {
                        mostrarError('El QR no contiene datos validos');
                    }
                    return;
                }
            } catch (e) {}
        }
        
        requestAnimationFrame(scanLoop);
    }

    // ============================================
    // MOSTRAR RESULTADOS
    // ============================================
    function mostrarResultado(usuario) {
        loading.classList.remove('show');
        
        if (usuario.id && usuarioActualId && String(usuario.id) === String(usuarioActualId)) {
            resultIcon.className = 'result-icon error';
            resultIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            resultTitle.textContent = 'No permitido!';
            resultInfo.innerHTML = '<p style="text-align:center;">No puedes escanearte a ti mismo.</p>';
            resultModal.classList.add('show');
            usuarioEncontrado = null;
            return;
        }
        
        usuarioEncontrado = usuario;
        resultIcon.className = 'result-icon';
        resultIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
        resultTitle.textContent = 'Usuario Encontrado!';
        resultInfo.innerHTML = 
            '<div class="result-info-item"><span class="result-info-label">Nombre:</span><span class="result-info-value">' + (usuario.nombre || '') + ' ' + (usuario.apellidos || '') + '</span></div>' +
            '<div class="result-info-item"><span class="result-info-label">CI:</span><span class="result-info-value">' + (usuario.ci || '') + '</span></div>' +
            '<div class="result-info-item"><span class="result-info-label">Cargo:</span><span class="result-info-value">' + (usuario.cargo || 'estudiante') + '</span></div>' +
            (usuario.id ? '<div class="result-info-item"><span class="result-info-label">ID:</span><span class="result-info-value">' + usuario.id + '</span></div>' : '');
        resultModal.classList.add('show');
    }

    function mostrarError(mensaje) {
        loading.classList.remove('show');
        resultIcon.className = 'result-icon error';
        resultIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
        resultTitle.textContent = 'QR No Valido';
        resultInfo.innerHTML = '<p style="text-align:center;">' + mensaje + '</p>';
        resultModal.classList.add('show');
    }

    // ============================================
    // LISTA DE USUARIOS
    // ============================================
    function actualizarLista() {
        var count = scannedUsers.length;
        scannedCount.textContent = count;
        listCount.textContent = count;
        scannedList.classList.toggle('show', count > 0);
        
        scannedItems.innerHTML = '';
        scannedUsers.forEach(function(user, index) {
            var tag = document.createElement('div');
            tag.className = 'scanned-tag';
            tag.innerHTML = user.nombre + ' ' + user.apellidos + ' (' + user.ci + ') <i class="fas fa-times-circle" onclick="eliminarUsuario(' + index + ')"></i>';
            scannedItems.appendChild(tag);
        });
    }
    
    window.eliminarUsuario = function(index) {
        scannedUsers.splice(index, 1);
        actualizarLista();
    };
    
    function agregarUsuario(usuario) {
        if (usuario.id && usuarioActualId && String(usuario.id) === String(usuarioActualId)) {
            alert('No puedes agregarte a ti mismo.');
            return false;
        }
        var existe = scannedUsers.some(function(u) {
            return (u.id && usuario.id && String(u.id) === String(usuario.id)) || 
                   (u.ci && usuario.ci && String(u.ci).trim() === String(usuario.ci).trim());
        });
        if (!existe) {
            scannedUsers.push(usuario);
            actualizarLista();
            return true;
        }
        return false;
    }

    // ============================================
    // EVENTOS
    // ============================================
    switchCameraBtn.addEventListener('click', switchCamera);
    torchBtn.addEventListener('click', toggleTorch);

    btnAgregarYSeguir.addEventListener('click', function() {
        if (usuarioEncontrado) agregarUsuario(usuarioEncontrado);
        resultModal.classList.remove('show');
        usuarioEncontrado = null;
        stopCamera();
        setTimeout(startCamera, 300);
    });

    btnSoloAgregar.addEventListener('click', function() {
        if (usuarioEncontrado) agregarUsuario(usuarioEncontrado);
        resultModal.classList.remove('show');
        usuarioEncontrado = null;
    });

    btnFinalizar.addEventListener('click', function() {
        stopCamera();
        if (scannedUsers.length > 0) {
            var jsonUsers = JSON.stringify(scannedUsers);
            var encodedUsers = btoa(unescape(encodeURIComponent(jsonUsers)));
            window.location.href = 'notificar.php?escaneados=' + encodedUsers;
        } else {
            window.location.href = 'notificar.php';
        }
    });

    // ============================================
    // INICIO Y LIMPIEZA
    // ============================================
    window.addEventListener('load', function() {
        startCamera();
    });

    window.addEventListener('beforeunload', function() {
        stopCamera();
    });

    // Para WebView: también limpiar cuando la página se oculta
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopCamera();
        }
    });

    // Para WebView: limpiar cuando la página se descarga
    window.addEventListener('pagehide', function() {
        stopCamera();
    });
    </script>
</body>
</html>