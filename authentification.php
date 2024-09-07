<!DOCTYPE html>
<html lang='ru'>
      <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
      <style type="text/css">
        .login-button {
          position: relative;
          cursor: pointer;
          background: none;
          border: none;
          color: white;
          font-size: x-large;
          text-decoration: none;
        }
        .login-button::after {
          position: absolute;
          content: '';
          bottom: 0;
          display: block;
          width: 0;
          height: 2px;
          background: white;
          left: 50%;
          transition: .3s;
          transform: translateX(-50%);
        }
        #reg::after {
          width: 100%;
        }
      </style>
      <?php include "includes/header.php" ?>
      <section class='img-back' style='background-image: url("https://img.freepik.com/free-vector/blank-blue-halftone-background_53876-114466.jpg?w=740&t=st=1708763905~exp=1708764505~hmac=6db7a15f0403e0b24c7aa3816621be4f059eebf84e53773d64d2267cc269640c"); width: 100%; display: flex; flex-direction: column; align-items: center; gap: 20px; align-self: center; border-radius: 20px; padding-top: 10px; padding-bottom: 10px; margin-top: 25px;'>
        <section style='display: flex; justify-content: space-around; width: 100%;'>
          <button onclick='open_reg(); return false;' class='login-button' id='reg'>Зарегистрироваться</button>
          <button onclick='open_login(); return false;' class='login-button' id='login'>Войти</button>
        </section>
        <div class="gallery js-flickity" id='gallery js-flickity' style='width: 100%;' data-flickity-options='{"wrapAround": false, "draggable": false}'>
        <div class="gallery-cell" style='width: 100%;'>
          <section style='display: flex; flex-direction: column; width: 85%; gap: 10px; color: white;' id='registration'>
            <label for='name'>Ваше имя:</label>
            <input name='name' id='name' required>
            <label for='surname'>Ваша фамилия:</label>
            <input name='surname' id='surname' required>
            <label for='phone'>Ваш номер телефона:</label>
            <input name='phone' id='phone' type='tel' required>
            <label for='email'>Ваша почта:</label>
            <input name='email' id='email' type='email' required>
            <label for='password'>Ваш пароль:</label>
            <input name='password' id='password' type='password' required>
            <button onclick='register();'>Зарегистрироваться</button>
          </section></div>
          <div class='gallery-cell' style='width: 100%;'>
          <section style='display: flex; flex-direction: column; width: 85%; gap: 10px; color: white;' id='login'>
            <label for='phone2'>Ваш номер телефона:</label>
            <input name='phone2' id='phone2' type='tel' required>
            <label for='password2'>Ваш пароль:</label>
            <input name='password2' id='password2' type='password' required>
            <button onclick='login();'>Войти</button>
          </section></div>
        </div>
      </section>
<?php include "includes/footer.html" ?>
</div>
</body>
<style>
.next, .previous {
  display: none;
}
button {
    cursor: pointer;
    border: solid white 1px;
    border-radius: 10px;
    background-color: rgba(255, 255, 255, 0.5);
    color: white;
    padding: 5px;
    font-size: large;
}
</style>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.js"></script>
<script type="text/javascript">
function register() {
  fetch('includes/scripts/register.php', {
      method: 'POST',
      mode: 'same-origin',
      credentials: 'include',
      body: JSON.stringify({
        name: document.getElementById("name").value,
        surname: document.getElementById("surname").value,
        phone: document.getElementById("phone").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value
        })
      })
      .then((response) => response.json())
      .then((data) => {
        if (data.status == 'success') {
          alert('Успешная регистрация!');
          window.open('my.php', '_self');
        }
        else {
          alert('Что-то пошло не так. Проверьте правильность введенных данных');
          window.open('authentification.php', '_self');
        }
      });
}

function login() {
  fetch('includes/scripts/login.php', {
      method: 'POST',
      mode: 'same-origin',
      credentials: 'include',
      body: JSON.stringify({
        phone: document.getElementById("phone2").value,
        password: document.getElementById("password2").value })
      })
      .then((response) => response.json())
      .then((data) => {
        if (data.status == 'success') {
          alert('Успешная авторизация!');
          window.open('my.php', '_self');
        }
        else if (data.status == 'admin') {
          window.open('admin.php', '_self');
        }
        else {
          alert('Не удалось войти в личный кабинет. Проверьте данные');
          window.open('authentification.php', '_self');
        }
      });
}
function open_reg() {
  document.getElementsByClassName('previous')[0].click();
  let style = document.createElement('style');
  style.innerHTML = '#login::after {width: 0;} #reg::after {width: 100%;}';
  document.head.appendChild(style);
}
function open_login() {
  document.getElementsByClassName('next')[0].click();
  let style = document.createElement('style');
  style.innerHTML = '#reg::after {width: 0;} #login::after {width: 100%;}';
  document.head.appendChild(style);
}
</script>
</html>
