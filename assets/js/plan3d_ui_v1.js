// public/js/plan3d_ui_v1.js
document.addEventListener('DOMContentLoaded', () => {
    // éléments UI
    const searchInput = document.getElementById('searchInput');
    const autocompleteList = document.getElementById('autocompleteList');
    const listContainer = document.getElementById('commercantList');
    const categoryFilter = document.getElementById('categoryFilter');

    // attendre que PLAN3D soit prêt
    if (!window.PLAN3D || !window.PLAN3D.data) {
        console.error('PLAN3D data not found. Assure-toi que plan3d_v1.js est chargé avant plan3d_ui_v1.js');
        return;
    }

    const data = window.PLAN3D.data; // tableau d'objets {name, category, ...}

    // utilitaire : retourne indices des éléments filtrés
    function filterData(query = '', category = '') {
        const q = (query || '').trim().toLowerCase();
        return data
            .map((d, i) => ({ d, i }))
            .filter(({ d }) => {
                const okName = q === '' || d.name.toLowerCase().includes(q);
                const okCat = !category || d.category === category;
                return okName && okCat;
            });
    }

    // Met à jour la liste principale (colonne gauche)
    function updateList() {
        const q = searchInput.value;
        const cat = categoryFilter ? categoryFilter.value : '';
        const results = filterData(q, cat);

        listContainer.innerHTML = '';
        if (results.length === 0) {
            const li = document.createElement('li');
            li.className = 'list-group-item text-muted';
            li.innerText = 'Aucun commerce trouvé';
            listContainer.appendChild(li);
            return;
        }

        results.forEach(({ d, i }) => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.style.cursor = 'pointer';
            li.innerHTML = `<div class="d-flex justify-content-between align-items-center">
                        <div>
                          <strong>${d.name}</strong><br>
                          <small class="text-muted">${d.category || ''}</small>
                        </div>
                        <div>
                          <button class="btn btn-sm btn-outline-dark">Voir</button>
                        </div>
                      </div>`;
            li.addEventListener('click', () => {
                // appelle la fonction exposée par le plan 3D
                window.PLAN3D.showMagasinByIndex(i);
            });
            listContainer.appendChild(li);
        });
    }

    // Autocomplétion (liste déroulante)
    function updateAutocomplete() {
        const q = searchInput.value;
        const cat = categoryFilter ? categoryFilter.value : '';
        autocompleteList.innerHTML = '';

        if (!q || q.trim().length === 0) {
            autocompleteList.style.display = 'none';
            return;
        }

        const results = filterData(q, cat).slice(0, 6); // max 6 suggestions
        if (results.length === 0) {
            autocompleteList.style.display = 'none';
            return;
        }

        results.forEach(({ d, i }) => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.style.cursor = 'pointer';
            li.innerHTML = `<strong>${d.name}</strong> <small class="text-muted">— ${d.category || ''}</small>`;
            li.addEventListener('click', () => {
                searchInput.value = d.name;
                autocompleteList.style.display = 'none';
                window.PLAN3D.showMagasinByIndex(i);
            });
            autocompleteList.appendChild(li);
        });
        // align width with input
        const rect = searchInput.getBoundingClientRect();
        autocompleteList.style.width = Math.round(rect.width) + 'px';
        autocompleteList.style.display = 'block';
    }

    // Events
    searchInput.addEventListener('input', () => {
        updateAutocomplete();
        updateList();
    });

    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            autocompleteList.style.display = 'none';
        }
    });

    categoryFilter && categoryFilter.addEventListener('change', () => {
        updateAutocomplete();
        updateList();
    });

    // click outside to close autocomplete
    document.addEventListener('click', (e) => {
        if (!autocompleteList.contains(e.target) && e.target !== searchInput) {
            autocompleteList.style.display = 'none';
        }
    });


    // initial render of list (all)
    updateList();

    // expose a helper for debugging if needed
    window.PLAN3D_UI = { updateList, updateAutocomplete };

});
