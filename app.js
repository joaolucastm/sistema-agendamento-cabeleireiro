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

delegate('#signUp', 'submit', signUp);
delegate('#login', 'submit', login);

async function login(e)
{
	e.preventDefault();
	console.log(await request('post', 'api/', new FormData(e.target)))
}

async function signUp(e)
{
	e.preventDefault();
	console.log(await request('post', 'api/', new FormData(e.target)))
}

async function listUsers()
{
	const data = new FormData();
	data.append("action", "listUsers");

	const users = await request('post', 'api/', data);
	
	users.data.forEach((user, index) => {
		append('body', 'p', 'class', user.name);
	});
}

listUsers();
