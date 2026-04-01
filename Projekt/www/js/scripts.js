/* Zmiana motywu */
{
	document.addEventListener('DOMContentLoaded', () => {
		const themeToggle = document.getElementById('theme-toggle');
		const rootElement = document.documentElement;

		const savedTheme = localStorage.getItem('theme');
		if (savedTheme === 'dark') {
			rootElement.setAttribute('data-theme', 'dark');
		}

		themeToggle.addEventListener('click', () => {
			const currentTheme = rootElement.getAttribute('data-theme');

			if (currentTheme === 'dark') {
				rootElement.removeAttribute('data-theme');
				localStorage.setItem('theme', 'light');
			} else {
				rootElement.setAttribute('data-theme', 'dark');
				localStorage.setItem('theme', 'dark');
			}
		});
	});
}

/* Menu mobilne */
{
	document.addEventListener('DOMContentLoaded', () => {
		const mobileBtn = document.getElementById('mobile-menu-btn');
		const mainNav = document.getElementById('main-nav');

		mobileBtn.addEventListener('click', () => {
			document.body.classList.toggle('menu-is-open');
		});

		const navLinks = mainNav.querySelectorAll('a');
		navLinks.forEach(link => {
			link.addEventListener('click', () => {
				document.body.classList.remove('menu-is-open');
			});
		});
	});
}

/* PWA */
{
	if ('serviceWorker' in navigator) {
		window.addEventListener('load', () => {
			navigator.serviceWorker.register('/sw.js')
				.then(registration => {
					console.log('Service Worker zarejestrowany z sukcesem. Scope:', registration.scope);
				})
				.catch(error => {
					console.error('Błąd rejestracji Service Workera:', error);
				});
		});
	}
}