const razdels = new Map();
razdels.set('Женская одежда', ['Блузка', 'Рубашка', 'Брюки', 'Джемпер', 'Водолазка', 'Кардиган', 'Джинсы', 'Комбинезон', 'Костюм', 'Лонгслив', 'Пиджак', 'Жилет', 'Жакет', 'Платье', 'Сарафан', 'Толстовка', 'Свитшот', 'Худи', 'Туника', 'Футболка', 'Топ' , 'Халат', 'Шорты', 'Юбка', 'Белье']);
razdels.set('Мужская одежда',  ['Блузка', 'Рубашка', 'Брюки', 'Джемпер', 'Водолазки', 'Кардиган', 'Джинсы', 'Комбинезон', 'Костюм', 'Лонгслив', 'Пиджак', 'Жилет', 'Жакеты', 'Толстовка', 'Свитшот', 'Худи', 'Футболка' , 'Халат', 'Шорты', 'Белье']);
razdels.set('Детская одежда', ['Блузка', 'Рубашка', 'Брюки', 'Джемпер', 'Водолазка', 'Кардиган', 'Джинсы', 'Комбинезон', 'Костюм', 'Лонгслив', 'Пиджак', 'Жилет', 'Жакет', 'Платье', 'Сарафан', 'Толстовка', 'Свитшот', 'Худи', 'Туника', 'Футболка', 'Топ' , 'Халат', 'Шорты', 'Юбка', 'Белье']);
razdels.set('Обувь', ['Сапоги', 'Ботинки', 'Туфли', 'Сандали', 'Кроссовки', 'Тапки']);
razdels.set('Аксессуары', ['Кепки', 'Шапки', 'Шляпы', 'Перчатки', 'Шарфы']);

razdels.forEach((value, key) => {
  section_main = document.createElement('section');
  section_main.setAttribute('style', "background-image: url('pictures/" + key + ".jpg');");
  section_main.classList.add('razdel');

  section_hover = document.createElement('section');
  section_hover.classList.add('razdel-hover');

  header = document.createElement('h2');
  header.innerText = key;
  section_hover.appendChild(header);

  section_buttons = document.createElement('section');
  section_buttons.classList.add('buttons-razdel');

  for (let item of value) {
    button = document.createElement('button');
    button.type = 'submit';
    button.innerText = item;
    button.setAttribute('onClick', 'window.open("search.php?text=' + item.replaceAll(' ', '+') + '&category=' + key.replace(' ', '+') + '", "_self");')
    section_buttons.appendChild(button);
  }
  section_hover.appendChild(section_buttons);
  section_main.appendChild(section_hover);

  let content = document.getElementById('catalog');
  content.appendChild(section_main);
})
