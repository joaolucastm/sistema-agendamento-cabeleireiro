async function request(method, url, form = null)
{
	try
	{
		const response = await fetch(url, {
			method: method.toUpperCase(),
			body: form ? new FormData(form) : null,
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
