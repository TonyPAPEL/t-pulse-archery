document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.menu-toggle');
  const navigation = document.querySelector('.main-nav');

  document.querySelectorAll('.tpulse-obfuscated-email').forEach((element) => {
    const user = element.getAttribute('data-user');
    const domain = element.getAttribute('data-domain');

    if (!user || !domain) return;

    const email = `${user}@${domain}`;
    const link = document.createElement('a');
    link.href = `mailto:${email}`;
    link.textContent = email;
    link.className = element.className;
    element.replaceWith(link);
  });

  if (!toggle || !navigation) return;

  toggle.addEventListener('click', () => {
    const open = navigation.classList.toggle('open');
    toggle.setAttribute('aria-expanded', String(open));
  });
});
