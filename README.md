# Bricks

<h3>Установка</h3>
  <p>
    <strong>Только через composer</strong>. При этом не забудьте о том, что при обновлении композера отвалится tinymce (какая-то несовместимость версий - отсутствуют необходимые для ЦМС плагины). Поэтому копируем папку /application/third_party/tinymce в безопасное место. Делаем:
  </p>
  <pre>composer require trigur/bricks</pre>

  <p>
    Удаляем заново установленный tinymce, и возвращаем старый на место.
  </p>

  <p>
    Идем в админку - в меню - "Модули" -> "Все модули". На владке "Установить модули" - устанавливаем модуль "Кирпичики". Если возникают проблемы - пишите в теме.
  </p>

<h3>Использование</h3>:

<ol>
  <li>1. Создание схемы блока:
  - Указываете название (машинное имя латиницей). Название используется также и в качестве названия шаблона блока.
  - Указываете заголовок. Используется для понимания человеком.
  - Добавляете поля. По схеме выше. Плюс указываете тип поля. Сейчас доступно четыре - input, textarea, file, image.</li>
  <li>2. Группы блоков. Вы можете сделать несколько групп для того, чтобы вызывать разные наборы блоков в разных местах одной страницы.</li>
  <li>3. Создание блока: Выбираете схему - нажимаете плюс - заполняете указанные поля.</li>
  <li>4. Создаете шаблон блока в папке /templates/имя_вашего_шаблона/bricks/название_схемы_блока. В шаблоне будут доступны по названию поля, которые вы указали в схеме + поля name (название) и title (заголовок) самого блока. Плюс при вызове блока можно передавать свой набор данных.</li>
  <li>5. Далее идете в категорию, или страницу - обнаруживаете вкладку "Дополнения модулей". На ней вы можете выбрать блоки, которые будут там отображаться. Порядок блоков можно менять.</li>
  <li>6. После установки модуля появляется возможность вызывать блоки у себя в шаблоне:
  <pre>Функция <b>contentBricks</b>:
    - $content - передаете $page, или $category. Или ничего. Тогда данных будут браться из ядра, но это лишний запрос в бд. Если у вас один шаблон для страниц и категорий, то сначала идет проверка на наличие блоков страницы. Если есть - выдаются они. Если нет - выдаются блоки категории страницы.
    - $groupName - название группы.
    - $data - дополнительные данные, которые вы можете передать в блок.
    - $prefix, $suffix - две переменные для оборачивания блоков. Может понадобиться при верстке для отделения блоков друг от друга.</pre>
  <pre>Функция <b>categoryBricks</b>:
    - $categoryId - id категории.
    - $groupName - см. выше. 
    - $data - см. выше. 
    - $prefix, $suffix - см. выше.</pre>
  <pre>Функция <b>pageBricks</b>:
    - $pageId - id страницы.
    - $groupName - см. выше. 
    - $data - см. выше. 
    - $prefix, $suffix - см. выше.</pre>
  <pre>Функция <b>getBrick</b>:
    - $brickName - название блока. 
    - $data - см. выше.</pre>
  <pre>Функция <b>getBricksByGroup</b>:
    - $groupName - название блока. 
    - $data - см. выше. 
    - $prefix, $suffix - см. выше.</pre></li>
</ol>

<h3>История изменений</h3>
  <ol>
    <li>
      v0.4 
      <ul>
        <li>- добавлено кэширование блоков;</li>
        <li>- добавлен вызов группы блоков безотносительно к контенту.</li>
      </ul>
    </li>
  </ol>

<p>
  Пока логика модуля недостаточно простая. Думаю над упрощением.
</p>

<p>
  Если есть пожелания и предложения - пишите: trigur@yandex.ru
</p>
 
