// Zmiana motywu
{
	const toggleBtn = document.getElementById('theme-toggle');
	const currentTheme = localStorage.getItem('theme');

	if (currentTheme === 'dark') {
		document.documentElement.setAttribute('data-theme', 'dark');
		toggleBtn.textContent = "Zmień motyw na jasny";
	}

	toggleBtn.addEventListener('click', () => {
		let theme = document.documentElement.getAttribute('data-theme');

		if (theme === 'dark') {
			document.documentElement.removeAttribute('data-theme');
			localStorage.setItem('theme', 'light');
			toggleBtn.textContent = "Zmień motyw na ciemny";
		} else {
			document.documentElement.setAttribute('data-theme', 'dark');
			localStorage.setItem('theme', 'dark');
			toggleBtn.textContent = "Zmień motyw na jasny";
		}
	});
}