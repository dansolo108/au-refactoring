<div class="deal-wheel">
    <!-- блок с призами -->
    <ul class="spinner"></ul>
    <!-- язычок барабана -->
    <div class="ticker"></div>
    <!-- кнопка -->
  </div>
  <div class="wheel-form__title">Скидка, бесплатная доставка или бонусные баллы на счет?</div>
    <div class="wheel-form__description">Испытайте фортуну и получите дополнительный бонус на покупки на сайте и в фирменных шоу-румах Autentiments</div>
  <div class="wheel-form">
    <button class="btn-spin" type="button" onClick="ym(86113805,'reachGoal','wheelSpin');">Крутить колесо</button>
    <button class="btn-getbonus" type="button" style="display: none;">Получить бонус</button>
    {$_modx->runSnippet('!AjaxForm', [
        'form' => 'wheelOfFortune-form',
        'formName' => 'Колесо удачи',
        'formFields' => 'tel,prize',
        'fieldNames' => 'tel==Телефон,prize==Приз',
        'hooks' => 'addToMaxma,FormItSaveForm'
    ])}
  </div>
  
  {'
  <script>
    // надписи и цвета на секторах
  const prizes = [
  {
    text: "Подарок к заказу",
    color: "hsl(274 93% 95%)",
    bonus: "pdrk",
  },
  { 
    text: "-10% на sale",
    color: "hsl(283 23% 73%)",
    bonus: "frdm",
  },
  { 
    text: "-20% на платья и комбинезоны",
    color: "hsl(207 47% 93%)",
    bonus: "astr",
  },
  {
    text: "-10% на все",
    color: "hsl(207 34% 81%)",
    bonus: "mprl",
  },
  {
    text: "-10% на новую коллекцию",
    color: "hsl(55 100% 95%)",
    bonus: "vlt",
  },
  {
    text: "-15% на рубашки и блузы",
    color: "hsl(48 86% 76%)",
    bonus: "tlp",
  },
  {
    text: "-10% на пиджаки и жилеты",
    color: "hsl(343 100% 97%)",
    bonus: "scs",
  }
  ];
  
  // создаём переменные для быстрого доступа ко всем объектам на странице — блоку в целом, колесу, кнопке и язычку
  const wheel = document.querySelector(".deal-wheel");
  const spinner = wheel.querySelector(".spinner");
  const trigger = wheel.closest(".au-wheel").querySelector(".btn-spin");
  const ticker = wheel.querySelector(".ticker");
  
  const bonusgetter = wheel.closest(".au-wheel").querySelector(".btn-getbonus");
  const wheelForm = wheel.closest(".au-wheel").querySelector("#wheel_of_fortune");
  
  // на сколько секторов нарезаем круг
  const prizeSlice = 360 / prizes.length;
  // на какое расстояние смещаем сектора друг относительно друга
  const prizeOffset = Math.floor(180 / prizes.length);
  // прописываем CSS-классы, которые будем добавлять и убирать из стилей
  const spinClass = "is-spinning";
  const selectedClass = "selected";
  // получаем все значения параметров стилей у секторов
  const spinnerStyles = window.getComputedStyle(spinner);
  
  // переменная для анимации
  let tickerAnim;
  // угол вращения
  let rotation = 0;
  // текущий сектор
  let currentSlice = 0;
  // переменная для текстовых подписей
  let prizeNodes;
  
  // расставляем текст по секторам
  const createPrizeNodes = () => {
  // обрабатываем каждую подпись
  prizes.forEach(({ text, color, bonus }, i) => {
    // каждой из них назначаем свой угол поворота
    const rotation = ((prizeSlice * i) * -1) - prizeOffset;
    // добавляем код с размещением текста на страницу в конец блока spinner
    spinner.insertAdjacentHTML(
      "beforeend",
      // текст при этом уже оформлен нужными стилями
      `<li class="prize" data-bonus=${bonus} style="--rotate: ${rotation}deg">
        <span class="text">${text}</span>
      </li>`
    );
  });
  };
  
  // рисуем разноцветные секторы
  const createConicGradient = () => {
  // устанавливаем нужное значение стиля у элемента spinner
  spinner.setAttribute(
    "style",
    `background: conic-gradient(
      from -90deg,
      ${prizes
        // получаем цвет текущего сектора
        .map(({ color }, i) => `${color} 0 ${(100 / prizes.length) * (prizes.length - i)}%`)
        .reverse()
      }
    );`
  );
  };
  
  // создаём функцию, которая нарисует колесо в сборе
  const setupWheel = () => {
  // сначала секторы
  createConicGradient();
  // потом текст
  createPrizeNodes();
  // а потом мы получим список всех призов на странице, чтобы работать с ними как с объектами
  prizeNodes = wheel.querySelectorAll(".prize");
  };
  
  // определяем количество оборотов, которое сделает наше колесо
  const spinertia = (min, max) => {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min + 1)) + min;
  };
  
  // функция запуска вращения с плавной остановкой
  const runTickerAnimation = () => {
  // взяли код анимации отсюда: https://css-tricks.com/get-value-of-css-rotation-through-javascript/
  const values = spinnerStyles.transform.split("(")[1].split(")")[0].split(",");
  const a = values[0];
  const b = values[1];  
  let rad = Math.atan2(b, a);
  
  if (rad < 0) rad += (2 * Math.PI);
  
  const angle = Math.round(rad * (180 / Math.PI));
  const slice = Math.floor(angle / prizeSlice);
  
  // анимация язычка, когда его задевает колесо при вращении
  // если появился новый сектор
  if (currentSlice !== slice) {
    // убираем анимацию язычка
    ticker.style.animation = "none";
    // и через 10 миллисекунд отменяем это, чтобы он вернулся в первоначальное положение
    setTimeout(() => ticker.style.animation = null, 10);
    // после того, как язычок прошёл сектор - делаем его текущим 
    currentSlice = slice;
  }
  // запускаем анимацию
  tickerAnim = requestAnimationFrame(runTickerAnimation);
  };
  
  // функция выбора призового сектора
  const selectPrize = () => {
  const selected = Math.floor(rotation / prizeSlice);
  prizeNodes[selected].classList.add(selectedClass);
  };
  
  let attempts = 0;
  let isShown = false;
  
  // отслеживаем нажатие на кнопку
  trigger.addEventListener("click", () => {
  // делаем её недоступной для нажатия
  trigger.disabled = true;
  // задаём начальное вращение колеса
  rotation = Math.floor(Math.random() * 360 + spinertia(2000, 5000));
  // убираем прошлый приз
  prizeNodes.forEach((prize) => prize.classList.remove(selectedClass));
  // добавляем колесу класс is-spinning, с помощью которого реализуем нужную отрисовку
  wheel.classList.add(spinClass);
  // через CSS говорим секторам, как им повернуться
  spinner.style.setProperty("--rotate", rotation);
  // возвращаем язычок в горизонтальную позицию
  ticker.style.animation = "none";
  // запускаем анимацию вращение
  runTickerAnimation();
  attempts++;
  trigger.textContent = `Крутить еще раз (${attempts}/3)`;
  if (!isShown) {
    bonusgetter.style.display = "block";
    isShown = true;
  }
  });
  
  // отслеживаем, когда закончилась анимация вращения колеса
  spinner.addEventListener("transitionend", () => {
  // останавливаем отрисовку вращения
  cancelAnimationFrame(tickerAnim);
  // получаем текущее значение поворота колеса
  rotation %= 360;
  // выбираем приз
  selectPrize();
  // убираем класс, который отвечает за вращение
  wheel.classList.remove(spinClass);
  // отправляем в CSS новое положение поворота колеса
  spinner.style.setProperty("--rotate", rotation);
  if (attempts >= 3) {
    trigger.textContent = "Попытки закончились";
  } else {
  // делаем кнопку снова активной
  trigger.disabled = false;
  }
  });
  
  bonusgetter.addEventListener("click", (e) => {
  
    bonusgetter.style.display = "none";
    trigger.style.display = "none";
  
    document.querySelector(".wheel-form__title").textContent = "Пожалуйста, введите свой номер телефона";
    document.querySelector(".wheel-form__description").textContent = "Мы отправим вам смс с промокодом на бонус";
  
    const bonus = wheel.querySelector(".selected").dataset.bonus;
    wheelForm.style.display = "block";
    wheelForm.querySelector("input[name=prize]").value = bonus;
  
  }, false)
  
  // подготавливаем всё к первому запуску
  setupWheel();
  
  
  $(document).on("af_complete", function(event, response) {
      var form = response.form;
      if (form.attr("id") == "wheel_of_fortune") {
        ym(86113805,"reachGoal","getBonus")
        localStorage.setItem("noNeedWheel", 1);
      }
  });
  
  </script>
  ' | jsToBottom}