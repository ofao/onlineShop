<HTML>
<?php include "includes/header.php" ?>
	<section style='font-size: 20px;'>
    <form action='' style='display: flex; flex-direction: column; padding: 20px; gap: 10px;'>
      <label for="theme">
    Тема вопроса:
      </label>
      <select id="theme" type="text" name="theme" style='font-size: 20px;'>
        <option value='1'>Аккаунт</option>
        <option value='2'>Каталог</option>
        <option value='3'>Товар</option>
        <option value='4'>Доставка</option>
      </select>
      <label for='description'>Опишите свой вопрос:
      </label>
      <textarea id='description' name='description' style='resize: none; min-height: 100%; font-size: 20px;'></textarea>
      <button type='submit' style='background-color: #a3cdf1; border: none; border-radius: 10px; font-size: 20px; font-family: "ANGST", serif;'>Отправить</button>
    </form>
  </section>
<?php include "includes/footer.html" ?>
</HTML>
