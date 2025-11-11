// Seleccionamos los elementos
const passwordGroup = document.querySelector('.login-form__password-group');
const passwordInput = document.querySelector('.login-form__input-Password');
const showIcon = document.querySelector('.login-form__icon--show');
const hideIcon = document.querySelector('.login-form__icon--hide');

// 🔹 Cuando el input obtiene el foco
passwordInput.addEventListener('focus', () => {
  passwordGroup.classList.add('active'); // muestra el icono
});

// 🔹 Cuando el input pierde el foco (clic fuera)
document.addEventListener('click', (event) => {
  const isClickInside = passwordGroup.contains(event.target);
  if (!isClickInside) {
    passwordGroup.classList.remove('active'); // oculta icono si clic fuera
    passwordInput.type = 'password'; // vuelve a ocultar la contraseña
    showIcon.style.display = 'block';
    hideIcon.style.display = 'none';
  }
});

// 🔹 Mostrar contraseña
showIcon.addEventListener('mousedown', (event) => {
  event.preventDefault(); // evita perder el foco
  passwordInput.type = 'text';
  showIcon.style.display = 'none';
  hideIcon.style.display = 'block';
  passwordInput.focus(); // mantiene el foco en el input
});

// 🔹 Ocultar contraseña
hideIcon.addEventListener('mousedown', (event) => {
  event.preventDefault(); // evita perder el foco
  passwordInput.type = 'password';
  hideIcon.style.display = 'none';
  showIcon.style.display = 'block';
  passwordInput.focus(); // mantiene el foco en el input
});