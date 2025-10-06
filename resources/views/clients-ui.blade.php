<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Clientes - UI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;max-width:1100px;margin:32px auto;padding:0 16px}
    h1{margin-bottom:8px}
    .card{border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin:16px 0;box-shadow:0 1px 2px rgba(0,0,0,.04)}
    label{display:block;font-size:14px;margin:8px 0 4px}
    input{width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px}
    .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
    .actions{display:flex;gap:8px;flex-wrap:wrap}
    button{padding:10px 14px;border:none;border-radius:10px;cursor:pointer}
    .btn-primary{background:#111827;color:#fff}
    .btn-secondary{background:#e5e7eb}
    .btn-danger{background:#ef4444;color:#fff}
    table{width:100%;border-collapse:collapse;margin-top:16px}
    th,td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left}
    tr:hover{background:#fafafa}
    .muted{color:#6b7280;font-size:14px}
    .badge{display:inline-block;padding:2px 8px;border-radius:9999px;background:#eef2ff;color:#3730a3;font-size:12px}
    .msg{margin-top:8px}
    .ok{color:#065f46}
    .err{color:#b91c1c}
  </style>
</head>
<body>
  <h1>Clientes</h1>


  <div class="card">
    <h2 id="formTitle">Crear cliente</h2>
    <div class="grid">
      <div>
        <label>Nombre</label>
        <input id="first_name" placeholder="Ej. Ana" />
      </div>
      <div>
        <label>Apellido</label>
        <input id="last_name" placeholder="Ej. López" />
      </div>
      <div>
        <label>Email</label>
        <input id="email" type="email" placeholder="ana@example.com" />
      </div>
      <div>
        <label>NIT</label>
        <input id="nit" placeholder="1234567-8" />
      </div>
    </div>
    <div class="actions" style="margin-top:12px">
      <button id="submitBtn" class="btn-primary">Guardar</button>
      <button id="cancelBtn" class="btn-secondary" style="display:none">Cancelar edición</button>
    </div>
    <div id="formMsg" class="msg"></div>
  </div>

  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <h2>Listado</h2>
      <button id="reloadBtn" class="btn-secondary">Recargar</button>
    </div>
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>NIT</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tbody"></tbody>
    </table>
    <div id="listMsg" class="msg"></div>
  </div>

<script>
const API_BASE = '/api/clients';
const tbody = document.getElementById('tbody');
const listMsg = document.getElementById('listMsg');
const formMsg = document.getElementById('formMsg');
const submitBtn = document.getElementById('submitBtn');
const cancelBtn = document.getElementById('cancelBtn');
const formTitle = document.getElementById('formTitle');

let editingId = null;

function getForm() {
  return {
    first_name: document.getElementById('first_name').value.trim(),
    last_name:  document.getElementById('last_name').value.trim(),
    email:      document.getElementById('email').value.trim(),
    nit:        document.getElementById('nit').value.trim()
  };
}
function setForm(c = {first_name:'', last_name:'', email:'', nit:''}) {
  document.getElementById('first_name').value = c.first_name || '';
  document.getElementById('last_name').value  = c.last_name  || '';
  document.getElementById('email').value      = c.email      || '';
  document.getElementById('nit').value        = c.nit        || '';
}
function setMsg(el, text, ok=true){ el.textContent = text || ''; el.className = 'msg ' + (ok ? 'ok' : 'err'); }

async function loadList() {
  listMsg.textContent = 'Cargando...';
  try {
    const res = await fetch(API_BASE, { headers: { 'Accept':'application/json' } });
    const json = await res.json();
    const items = Array.isArray(json) ? json : json.data;
    tbody.innerHTML = '';
    (items || []).forEach(c => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${c.id}</td>
        <td>${c.first_name ?? ''}</td>
        <td>${c.last_name ?? ''}</td>
        <td>${c.email ?? ''}</td>
        <td>${c.nit ?? ''}</td>
        <td class="actions">
          <button class="btn-secondary" data-edit="${c.id}">Editar</button>
          <button class="btn-danger" data-del="${c.id}">Eliminar</button>
        </td>`;
      tbody.appendChild(tr);
    });
    setMsg(listMsg, `Total: ${(items || []).length}`, true);
  } catch (e) {
    setMsg(listMsg, 'Error al cargar la lista', false);
    console.error(e);
  }
}

async function createClient() {
  formMsg.textContent = 'Guardando...';
  try {
    const body = JSON.stringify(getForm());
    const res = await fetch(API_BASE, {
      method:'POST',
      headers: { 'Content-Type':'application/json', 'Accept':'application/json' },
      body
    });
    if (res.status === 201) {
      const json = await res.json();
      setMsg(formMsg, 'Creado correctamente', true);
      setForm();
      await loadList();
      return;
    }
    const err = await res.json().catch(()=>({}));
    setMsg(formMsg, 'Error al crear: ' + (err?.message || res.status), false);
  } catch (e) {
    setMsg(formMsg, 'Error de red al crear', false);
    console.error(e);
  }
}

async function updateClient() {
  formMsg.textContent = 'Actualizando...';
  try {
    const body = JSON.stringify(getForm()); // OJO: tu API valida TODOS los campos
    const res = await fetch(`${API_BASE}/${editingId}`, {
      method:'PUT',
      headers: { 'Content-Type':'application/json', 'Accept':'application/json' },
      body
    });
    if (res.ok) {
      setMsg(formMsg, 'Actualizado correctamente', true);
      editingId = null;
      formTitle.textContent = 'Crear cliente';
      submitBtn.textContent = 'Guardar';
      cancelBtn.style.display = 'none';
      setForm();
      await loadList();
      return;
    }
    const err = await res.json().catch(()=>({}));
    setMsg(formMsg, 'Error al actualizar: ' + (err?.message || res.status), false);
  } catch (e) {
    setMsg(formMsg, 'Error de red al actualizar', false);
    console.error(e);
  }
}

async function deleteClient(id) {
  if (!confirm('¿Eliminar cliente #' + id + '?')) return;
  try {
    const res = await fetch(`${API_BASE}/${id}`, { method:'DELETE' });
    if (res.status === 204) {
      await loadList();
      return;
    }
    const err = await res.json().catch(()=>({}));
    alert('No se pudo eliminar: ' + (err?.message || res.status));
  } catch (e) {
    alert('Error de red al eliminar');
    console.error(e);
  }
}

tbody.addEventListener('click', async (ev) => {
  const editId = ev.target?.dataset?.edit;
  const delId  = ev.target?.dataset?.del;
  if (editId) {
    try {
      const res = await fetch(`${API_BASE}/${editId}`, { headers: { 'Accept':'application/json' }});
      if (!res.ok) return alert('No encontrado');
      const json = await res.json();
      const c = Array.isArray(json) ? json[0] : (json.data || json);
      editingId = c.id;
      setForm(c);
      formTitle.textContent = 'Editar cliente #' + editingId;
      submitBtn.textContent = 'Actualizar';
      cancelBtn.style.display = 'inline-block';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch (e) {
      alert('Error al cargar el cliente');
      console.error(e);
    }
  }
  if (delId) {
    deleteClient(delId);
  }
});

submitBtn.addEventListener('click', () => {
  if (editingId) updateClient(); else createClient();
});
cancelBtn.addEventListener('click', () => {
  editingId = null;
  setForm();
  formTitle.textContent = 'Crear cliente';
  submitBtn.textContent = 'Guardar';
  cancelBtn.style.display = 'none';
  formMsg.textContent = '';
});

document.getElementById('reloadBtn').addEventListener('click', loadList);

loadList();
</script>
</body>
</html>
