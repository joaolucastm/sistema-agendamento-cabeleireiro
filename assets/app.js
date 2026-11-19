const $ = selector => document.querySelector(selector);

const $$ = selector => document.querySelectorAll(selector);

const on = (selector, event, callback) => {
	$$(selector).forEach(el => el.addEventListener(event, callback));
};

const delegate = (selector, event, callback) => { 
	document.addEventListener(event, eventObject => {
		const target = eventObject.target?.closest?.(selector);
		if (target) callback(eventObject, target);
	});
};

const append = (parentSelector, tag, idOrClass, content) => {
	const p = $(parentSelector) ?? $('body');
	const el = document.createElement(tag);
	
	if (idOrClass?.[0] === '#') el.id = idOrClass.slice(1);	
	else if (idOrClass?.[0] === '.') el.className = idOrClass.slice(1);
		
	el.innerHTML = content ?? '';
	p.appendChild(el);

	return el;
}

async function request(method, url, body = null)
{
	try
	{
		const response = await fetch(url, {
			method: method.toUpperCase(),
			body,
			credentials: 'include',
		});
		
		return await response.json();
	}
	catch(e)
	{
		console.error(e);
		return false;
	}
}

async function handleRequest(e)
{
	e.preventDefault();

	const btn = e.submitter;
	if (btn?.disabled) return;
	if (btn) btn.disabled = true;
	
	const result = await request('post', api, new FormData(e.target));
	
	notify(result.success ? 'success' : 'error', result.message);
	
	setTimeout(() => { if (btn) btn.disabled = false }, 2000);
	
	return result.success;
}

function notify(style, message)
{
	append('body', 'div', `.notification ${style}`, message);
}

function redirect(url)
{
	window.location.href = url;
}

function getPage()
{
	const path = window.location.pathname;
	const parts = path.split('/').filter(Boolean);
	const page = parts[parts.length - 1];
	return page;
}

function normalizeText(text) {
	return text.toLowerCase().normalize("NFD").replace(/\p{Diacritic}/gu, "");
}


const api = 'api/';

delegate('#signUp', 'submit', handleRequest);
delegate('#login', 'submit', async (e) => {
	const success = await handleRequest(e);
	if (success) setTimeout(() => redirect('painel.php'), 1000);
});
delegate('#createAppointment', 'submit', async (e) => {
	await handleRequest(e) && listClientAppointments();
});

async function listClientAppointments()
{
	const data = new FormData();
	data.append("action", "listClientAppointments");

	const result = await request('post', api, data);

	if (!result.success)
	{
		notify(result.success ? 'success' : 'error', result.message); 
		return;
	}
	
	
	$('#clientAppointments').innerHTML = '';
	
	result.data.forEach((item, index) => {
		const normStatus = normalizeText(item.status);
	
		append('#clientAppointments', 'tr', '', `
		
			<td>${item.id}</td>
			<td>${item.schedule}</td>
            <td><span class="badge st-${normStatus}">${item.status}</span></td>
            <td>
            	<form id='cancelAppointment'>
     	    		<input type='hidden' name='action' value='cancelAppointment'>
     	    		<input type='hidden' name='id' value='${item.id}'>
                	<button type="submit" class="btn btn-erro btn-sm">Cancelar</button>
            	</form>
            </td>
            
		`);
	});
}

const page = getPage();

if (page === 'painel.php') listClientAppointments();
