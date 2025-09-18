// public/js/plan3d_v1.js
// Version corrigée : fixes pointer-events labels, camera fitting, zoom "vers l'extérieur"

(function(){
    // ==== Setup ==============================================================
    const container = document.querySelector('.col-md-8'); // doit exister
    const planEl = document.getElementById('plan3d');

    // Scene & camera
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0xbfd1e5);

    const camera = new THREE.PerspectiveCamera(
        75,
        container.clientWidth / window.innerHeight,
        0.1,
        2000
    );

    // Renderer (WebGL)
    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: false });
    renderer.setPixelRatio(window.devicePixelRatio || 1);
    renderer.setSize(container.clientWidth, window.innerHeight);
    // ensure canvas sits absolutely inside the container
    renderer.domElement.style.position = 'absolute';
    renderer.domElement.style.top = '0';
    renderer.domElement.style.left = '0';
    renderer.domElement.style.width = '100%';
    renderer.domElement.style.height = '100%';
    planEl.appendChild(renderer.domElement);

    // OrbitControls
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.06;
    controls.minDistance = 5;
    controls.maxDistance = 200;
    controls.maxPolarAngle = Math.PI / 2 - 0.05;

    // CSS2DRenderer for labels
    const labelRenderer = new THREE.CSS2DRenderer();
    labelRenderer.setSize(container.clientWidth, window.innerHeight);
    labelRenderer.domElement.style.position = 'absolute';
    labelRenderer.domElement.style.top = '0';
    labelRenderer.domElement.style.left = '0';
    labelRenderer.domElement.style.pointerEvents = 'none'; // VERY IMPORTANT -> let pointer through
    labelRenderer.domElement.style.width = '100%';
    labelRenderer.domElement.style.height = '100%';
    planEl.appendChild(labelRenderer.domElement);

    // Lights
    scene.add(new THREE.AmbientLight(0x404040, 2));
    const dir = new THREE.DirectionalLight(0xffffff, 1);
    dir.position.set(5, 10, 7).normalize();
    scene.add(dir);

    // ==== Ground, roads, trees ==============================================
    const asphalt = new THREE.Mesh(new THREE.PlaneGeometry(80, 40), new THREE.MeshLambertMaterial({ color: 0x333333 }));
    asphalt.rotation.x = -Math.PI / 2;
    scene.add(asphalt);

    const grayZone = new THREE.Mesh(new THREE.PlaneGeometry(60, 30), new THREE.MeshLambertMaterial({ color: 0xaaaaaa }));
    grayZone.rotation.x = -Math.PI / 2;
    grayZone.position.set(0, 0.01, 0);
    scene.add(grayZone);

    function createTree(x, z) {
        const trunk = new THREE.Mesh(new THREE.CylinderGeometry(0.18, 0.18, 1.8), new THREE.MeshLambertMaterial({ color: 0x8b4513 }));
        trunk.position.set(x, 0.9, z);
        scene.add(trunk);
        const leaves = new THREE.Mesh(new THREE.ConeGeometry(0.9, 1.8, 8), new THREE.MeshLambertMaterial({ color: 0x228b22 }));
        leaves.position.set(x, 2.1, z);
        scene.add(leaves);
    }
    for (let i = -8; i <= 8; i += 4) {
        createTree(-30, i); createTree(30, i);
    }

    // ==== Cubes (group) ====================================================
    const magasins = [];
    const glowMeshes = [];
    const cubesGroup = new THREE.Group();
    scene.add(cubesGroup);

    const magasinInfos = [
        { name: "V. femme", desc: "Mode tendance", image: "https://picsum.photos/300/200?1", url: "#", category: "textile" },
        { name: "L. maison", desc: "Vêtements", image: "https://picsum.photos/300/200?2", url: "#", category: "textile" },
        { name: "F et legumes", desc: "Supermarché", image: "https://picsum.photos/300/200?3", url: "#", category: "alimentaire" },
        { name: "coffee", desc: "Café", image: "https://picsum.photos/300/200?4", url: "#", category: "alimentaire" },
        { name: "anas", desc: "Maison", image: "https://picsum.photos/300/200?5", url: "#", category: "bazar" },
        { name: "Tapis du Monde", desc: "Tapis", image: "https://picsum.photos/300/200?6", url: "#", category: "tapis" },
        { name: "Bijouterie Luxe", desc: "Bijoux", image: "https://picsum.photos/300/200?7", url: "#", category: "bijoux" },
        { name: "Parfumerie Fine", desc: "Parfums", image: "https://picsum.photos/300/200?8", url: "#", category: "parfums" }
    ];

    const categoryColors = {
        "alimentaire": 0xffcc66, "textile": 0x66ccff, "bazar": 0x99ff99, "vaisselles": 0xff9999,
        "bijoux": 0xff66ff, "parfums": 0xcc66ff, "tissus": 0x66ffcc, "tapis": 0xff9966
    };

    const magasinGeometry = new THREE.BoxGeometry(2, 1, 2);
    const columnX = [-20, -16, -12, -8, -4, 0, 4, 8, 12, 16];
    const cubesPerColumn = 3;
    const spacingZ = 3;
    const commerceColumns = [0, 2, 3, 6, 7, 9];

    let cubeIndex = 0;
    commerceColumns.forEach(col => {
        for (let row = 0; row < cubesPerColumn; row++) {
            if (cubeIndex >= magasinInfos.length) break;
            const data = magasinInfos[cubeIndex];
            const mat = new THREE.MeshLambertMaterial({ color: categoryColors[data.category] || 0xffffff });
            const cube = new THREE.Mesh(magasinGeometry, mat);
            cube.position.set(columnX[col], 0.5, (row - (cubesPerColumn - 1) / 2) * spacingZ);
            cube.userData = data;
            cubesGroup.add(cube);
            magasins.push(cube);

            // glow
            const glow = new THREE.Mesh(cube.geometry.clone(), new THREE.MeshBasicMaterial({ color: 0xffff00, transparent: true, opacity: 0.18 }));
            glow.position.copy(cube.position);
            glow.scale.set(1.08, 1.08, 1.08);
            scene.add(glow);
            glowMeshes.push(glow);

            // label
            const div = document.createElement('div');
            div.className = 'label';
            div.textContent = data.name;
            div.style.color = 'white';
            div.style.fontWeight = '600';
            div.style.padding = '2px 6px';
            div.style.background = 'rgba(0,0,0,0.5)';
            div.style.borderRadius = '4px';
            div.style.fontSize = '0.85rem';
            const label = new THREE.CSS2DObject(div);
            label.position.set(0, 1.2, 0);
            cube.add(label);

            cubeIndex++;
        }
    });

    // ==== Bounding sphere to fit camera ====================================
    const bbox = new THREE.Box3().setFromObject(cubesGroup);
    const sphere = bbox.getBoundingSphere(new THREE.Sphere());
    const sceneCenter = sphere.center.clone();
    const sceneRadius = sphere.radius;

    function fitCameraToScene(factor = 2.2) {
        // use bounding sphere and fov to compute distance
        const fov = camera.fov * Math.PI / 180;
        const distance = sceneRadius * factor / Math.sin(fov / 2);
        // place camera on diagonal so user sees plan at angle
        const pos = new THREE.Vector3(sceneCenter.x + distance, sceneCenter.y + distance * 0.45, sceneCenter.z + distance);
        camera.position.copy(pos);
        controls.target.copy(sceneCenter);
        controls.update();
    }
    fitCameraToScene(1.8); // initial fit (adjust factor if needed)
    function focusOnObject(object) {
        const box = new THREE.Box3().setFromObject(object);
        const center = box.getCenter(new THREE.Vector3());

        const distance = box.getSize(new THREE.Vector3()).length() * 2;
        const direction = new THREE.Vector3(0, 2, 4); // position caméra relative
        const newPos = center.clone().add(direction.multiplyScalar(distance / 4));

        new TWEEN.Tween(camera.position)
            .to({ x: newPos.x, y: newPos.y, z: newPos.z }, 1000)
            .easing(TWEEN.Easing.Quadratic.Out)
            .onUpdate(() => {
                camera.lookAt(center);
                controls.target.copy(center);
            })
            .start();
    }



    // ==== Highlight helper =================================================
    let highlight;
    function setHighlight(mesh) {
        if (highlight) scene.remove(highlight);
        if (!mesh) return;
        const edges = new THREE.EdgesGeometry(mesh.geometry);
        highlight = new THREE.LineSegments(edges, new THREE.LineBasicMaterial({ color: 0xffff00, linewidth: 2 }));
        highlight.position.copy(mesh.position);
        scene.add(highlight);
    }

    // ==== showMagasin : enlarge + recenter outwards (smooth) ================
    let cameraStartPos = null;
    let cameraTargetPos = null;
    let cameraProgress = 0;
// ==== updateMagasinModal ====
    function updateMagasinModal(mesh) {
        if (!mesh || !mesh.userData) return;
        const m = mesh.userData;

        // Mettre à jour le panneau gauche
        const titleEl = document.getElementById('magasinTitle');
        const imgEl   = document.getElementById('magasinImage');
        const descEl  = document.getElementById('magasinDescription');
        const linkEl  = document.getElementById('magasinLink');
        const details = document.getElementById('magasinDetails');

        if (titleEl)  titleEl.textContent = m.name || '';
        if (imgEl) {
            imgEl.src = m.image || '';
            imgEl.alt = m.name || 'Image magasin';
        }
        if (descEl)   descEl.textContent = m.desc || '';
        if (linkEl) {
            linkEl.href = m.url || '#';
            linkEl.setAttribute('aria-label', `Voir la fiche de ${m.name || ''}`);
        }
        if (details) details.style.display = 'block';

        // Trouver l'index du magasin exposé (si window.PLAN3D.data existe)
        let index = -1;
        if (window.PLAN3D && Array.isArray(window.PLAN3D.data)) {
            index = window.PLAN3D.data.findIndex(d =>
                (d.name === m.name && (d.category || '') === (m.category || ''))
            );
        }

        // Essayer d'appeler la fonction UI exposée (si elle existe)
        try {
            if (window.PLAN3D_UI && typeof window.PLAN3D_UI.highlightInList === 'function') {
                window.PLAN3D_UI.highlightInList(index);
                return;
            }
        } catch (e) {
            // ignore
        }

        // Sinon, dispatch un événement pour que l'UI s'y abonne
        const evt = new CustomEvent('magasin:selected', { detail: { index, data: m }});
        document.dispatchEvent(evt);
    }

    function showMagasin(magasin) {
        // Reset tous les cubes
        magasins.forEach(m => {
            m.scale.set(1, 1, 1);
        });

        // Animation d’agrandissement du cube sélectionné
        const targetScale = { x: magasin.scale.x, y: magasin.scale.y, z: magasin.scale.z };
        const newScale = { x: 1.5, y: 1.5, z: 1.5 };

        new TWEEN.Tween(targetScale)
            .to(newScale, 600) // durée 600ms
            .easing(TWEEN.Easing.Elastic.Out)
            .onUpdate(() => {
                magasin.scale.set(targetScale.x, targetScale.y, targetScale.z);
            })
            .start();

        // Focus caméra vers le cube sélectionné
        focusOnObject(magasin);

        // Met à jour le modal (colonne gauche)
        updateMagasinModal(magasin);
    }
   /* function showMagasin(mesh) {
        const m = mesh.userData;
        // fill left panel
        document.getElementById("magasinTitle").innerText = m.name;
        document.getElementById("magasinImage").src = m.image;
        document.getElementById("magasinDescription").innerText = m.desc;
        document.getElementById("magasinLink").href = m.url;
        document.getElementById("magasinDetails").style.display = "block";

        setHighlight(mesh);
        if (selectedCube && selectedCube !== mesh) selectedCube.scale.set(1, 1, 1);
        mesh.scale.set(1.5, 1.5, 1.5);
        selectedCube = mesh;

        // compute desired camera distance based on scene radius and screen size
        const width = renderer.domElement.clientWidth;
        const height = renderer.domElement.clientHeight;
        const screenFactor = Math.max(1, 800 / Math.min(width, height)); // small screens -> increase distance
        const desiredDistance = Math.max(sceneRadius * 1.6 * screenFactor, 12);

        // direction from target to current camera to keep similar angle
        const dir = camera.position.clone().sub(controls.target).normalize();
        const targetCamPos = mesh.position.clone().add(dir.multiplyScalar(desiredDistance));

        // prepare smooth interpolation inside animate()
        cameraStartPos = camera.position.clone();
        cameraTargetPos = targetCamPos.clone();
        cameraProgress = 0;

        // set controls target to cube position so rotation centers on it
        controls.target.copy(mesh.position);
    }*/

    // ==== Raycaster click ===================================================
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();
    renderer.domElement.addEventListener('click', (ev) => {
        const rect = renderer.domElement.getBoundingClientRect();
        mouse.x = ((ev.clientX - rect.left) / rect.width) * 2 - 1;
        mouse.y = -((ev.clientY - rect.top) / rect.height) * 2 + 1;
        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObjects(magasins, false);
        if (intersects.length > 0) {
            showMagasin(intersects[0].object);
        }
    });

    // ==== Animations (glow, bounce, camera interpolation) ===================
    let glowPhase = 0;
    let bouncePhase = 0;
    function animate() {
        requestAnimationFrame(animate);

        // glow pulse
        glowPhase += 0.05;
        glowMeshes.forEach(g => g.material.opacity = 0.08 + Math.abs(Math.sin(glowPhase)) * 0.18);

        // bounce
        bouncePhase += 0.03;
        magasins.forEach((c, i) => { c.position.y = 0.5 + Math.sin(bouncePhase + i) * 0.03; });

        // camera interpolation (smooth move if set)
        if (cameraTargetPos && cameraStartPos) {
            cameraProgress += 0.04; // speed
            if (cameraProgress >= 1) { cameraProgress = 1; }
            camera.position.lerpVectors(cameraStartPos, cameraTargetPos, easeOutCubic(cameraProgress));
            if (cameraProgress === 1) {
                cameraStartPos = null;
                cameraTargetPos = null;
                cameraProgress = 0;
            }
        }
        TWEEN.update();  // important !

        controls.update();
        renderer.render(scene, camera);
        labelRenderer.render(scene, camera);
    }
    animate();

    // easing
    function easeOutCubic(t){ return 1 - Math.pow(1 - t, 3); }

    // ==== Resize handling ===================================================
    window.addEventListener('resize', () => {
        const w = container.clientWidth;
        const h = window.innerHeight;
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        renderer.setSize(w, h);
        labelRenderer.setSize(w, h);
        // optional: refit camera if you want on every resize
        // fitCameraToScene(1.8);
    });

    // ==== Utility: expose showMagasin for the left panel list clicks =========
    window.PLAN3D = {
        showMagasinByIndex: function(idx){
            if (magasins[idx]) showMagasin(magasins[idx]);
        },
        fitCameraToScene,
        data: magasinInfos  // <-- expose les infos (nom, catégorie, image, etc.)
    };
    // ==== Debug helper (uncomment if needed) ================================
    // console.log('sceneRadius', sceneRadius, 'center', sceneCenter);
})();
