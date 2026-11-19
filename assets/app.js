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
		
	el.textContent = content ?? '';
	p.appendChild(el);
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

	const result = await request('post', api, new FormData(e.target));
	const status = result.success ? 'success' : 'error';
	const btn = e.submitter;
	
	btn.disabled = true;
	setTimeout(() => { if (btn) btn.disabled = false }, 2000);
	
	notify(status, result.message);
	
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

const api = 'api/';

delegate('#signUp', 'submit', handleRequest);
delegate('#login', 'submit', async (e) => {
	const success = await handleRequest(e);
	if (success) setTimeout(() => redirect('painel.php'), 1000);
});
delegate('#createAppointment', 'submit', handleRequest);

// async function login(e)
// {
// 	handleRequest(e);
// }
// 
// async function signUp(e)
// {
// 	handleRequest(e);
// }
// 
// async function createAppointment(e)
// {
// 	handleRequest(e);
// }

async function listUsers()
{
	const data = new FormData();
	data.append("action", "listUsers");

	const users = await request('post', api, data);
	
	users.data.forEach((user, index) => {
		append('body', 'p', 'class', user.name);
	});
}

// listUsers();
