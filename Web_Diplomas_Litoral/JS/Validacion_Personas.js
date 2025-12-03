const debounce = (fn, wait=400) => { let t; return (...args) => { clearTimeout(t); t = setTimeout(()=>fn.apply(this,args), wait); }; };

const form = document.getElementById('miFormulario');
const docInput = document.getElementById('document_id');
const errDoc = document.getElementById('error_document_id');
const email1 = document.getElementById('email_primary');
const errEmail1 = document.getElementById('error_email_primary');
const email2 = document.getElementById('email_secondary');
const errEmail2 = document.getElementById('error_email_secondary');

async function checkUnique(type, value) {
    const fd = new FormData(); fd.append('type', type); fd.append('value', value);
    const res = await fetch('validate_unique.php', { method:'POST', body: fd, credentials: 'same-origin' });
    try { return await res.json(); } catch (e) { return {ok:true}; }
}

const markError = (el, msgElem, msg) => {
    el.classList.add('input-error');
    msgElem.style.display = 'block';
    msgElem.textContent = msg;
};
const clearError = (el, msgElem) => {
    el.classList.remove('input-error');
    msgElem.style.display = 'none';
    msgElem.textContent = '';
};

docInput && docInput.addEventListener('input', debounce(async ()=>{
    const v = docInput.value.trim();
    if (!v) { clearError(docInput, errDoc); return; }
    const r = await checkUnique('document_id', v);
    if (!r.ok) markError(docInput, errDoc, 'Documento ya registrado'); else clearError(docInput, errDoc);
}));

email1 && email1.addEventListener('input', debounce(async ()=>{
    const v = email1.value.trim();
    if (!v) { clearError(email1, errEmail1); return; }
    const r = await checkUnique('email_primary', v);
    if (!r.ok) markError(email1, errEmail1, 'Email ya registrado'); else clearError(email1, errEmail1);
}));

email2 && email2.addEventListener('input', debounce(async ()=>{
    const v = email2.value.trim();
    if (!v) { clearError(email2, errEmail2); return; }
    const r = await checkUnique('email_secondary', v);
    if (!r.ok) markError(email2, errEmail2, 'Email secundario ya registrado'); else clearError(email2, errEmail2);
}));

form.addEventListener('submit', async (e)=>{
    // Ejecutar comprobaciones finales y evitar submit si hay errores
    e.preventDefault();
    let hasError = false;
    // document
    if (docInput) {
        const r = await checkUnique('document_id', docInput.value.trim());
        if (!r.ok) { markError(docInput, errDoc, 'Documento ya registrado'); hasError = true; }
    }
    if (email1) {
        const r = await checkUnique('email_primary', email1.value.trim());
        if (!r.ok) { markError(email1, errEmail1, 'Email ya registrado'); hasError = true; }
    }
    if (email2 && email2.value.trim()!=='') {
        const r = await checkUnique('email_secondary', email2.value.trim());
        if (!r.ok) { markError(email2, errEmail2, 'Email secundario ya registrado'); hasError = true; }
    }

    if (!hasError) {
        form.submit();
    }
});