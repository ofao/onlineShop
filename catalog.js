const razdels = new Map();
razdels.set('Женская одежда', ['Блузки и рубашки', 'Брюки', 'Верхняя одежда', 'Джемперы, водолазки и кардиганы', 'Джинсы', 'Комбинезоны', 'Костюмы', 'Лонгсливы', 'Пиджаки, жилеты и жакеты', 'Платья и сарафаны', 'Толстовки, свитшоты и худи', 'Туники', 'Футболки и топы' , 'Халаты', 'Шорты', 'Юбки', 'Белье']);
razdels.set('Мужская одежда',  ['Блузки и рубашки', 'Брюки', 'Верхняя одежда', 'Джемперы, водолазки и кардиганы', 'Джинсы', 'Комбинезоны', 'Костюмы', 'Лонгсливы', 'Пиджаки, жилеты и жакеты', 'Толстовки, свитшоты и худи', 'Футболки' , 'Халаты', 'Шорты', 'Белье']);
razdels.set('Детская одежда', ['Ясельная', 'Дошкольная', 'Школьная', 'Подростковая']);
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
    section_buttons.appendChild(button);
  }
  section_hover.appendChild(section_buttons);
  section_main.appendChild(section_hover);

  content = document.getElementById('content');
  content.appendChild(section_main);
})
