<?php
include('db.php');

// Параметры пагинации
$items_per_page = 6; // Количество товаров на странице
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Убедитесь, что значение страницы - целое число
$offset = ($page - 1) * $items_per_page; // Сдвиг для пагинации

// Получаем товары с пагинацией
$sql = "SELECT * FROM products LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);  // Привязываем параметр для LIMIT
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);  // Привязываем параметр для OFFSET
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем общее количество товаров
$sql = "SELECT COUNT(*) FROM products";
$total_items = $pdo->query($sql)->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GOTHSET</title>
    <link rel="stylesheet" href="./style.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="navbar">
      <div class="logo">GOTHSET</div>
      <div class="menu">
        <a href="#">О нас</a>
        <a href="#">Каталог</a>
        <a href="#">Контакты</a>
      </div>
      <?php if (isset($_COOKIE['username'])): ?>
        <div class="user-info">
    <!-- Ссылка на профиль -->
    <a href="profile.php"><?= htmlspecialchars($_COOKIE['username']) ?></a>
    <a href="logout.php" class="login-btn">Выйти</a>
</div>

      <?php else: ?>
      <div class="user-info">
        <a href="login.php" class="login-btn">Войти</a>
      </div>
      <?php endif; ?>
    </div>
    <!-- Header Section -->
    <div class="header">
      <h1 class="header-text1">Готические интерьеры</h1>
      <h1 class="header-text2">и стильный дизайн</h1>
      <p>
        Создаем индивидуальные дизайны квартиры или дома с учётом ваших
        потребностей и бюджета
      </p>
      <button class="call-btn" onclick="openCallModal()">Позвонить</button>
      <img src="./src/img/header.png" alt="Interior Image" />
    </div>
    <!-- Модальное окно для звонков -->
<div id="callModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h1>Позвонить нам</h1>
    <ul>
      <li><a href="tel:+1234567890"><i class="fa fa-phone"></i> +1 (234) 567-890</a></li>
      <li><a href="tel:+9876543210"><i class="fa fa-phone"></i> +9 (876) 543-210</a></li>
    </ul>
  </div>
</div>




    <!-- About Us Section -->
    <div class="about-us" id="about">
      <h2>О нас</h2>
      <div class="about-us-content">
        <div class="placeholder-image"></div>
        <div class="text-content">
          <p>
            Компания GOTHSET — это команда профессионалов, специализирующихся на
            создании уникальных готических интерьеров. Мы воплощаем в жизнь
            самые смелые идеи, создавая атмосферу роскоши, мистики и комфорта.
          </p>
          <p>
            С нами вы получите эксклюзивный дизайн, который подчеркнет ваш стиль
            и индивидуальность. Мы работаем с лучшими материалами и применяем
            современные технологии, чтобы создать пространство вашей мечты.
          </p>
          <button class="cta-button" onclick="openModal()">
            Связаться с нами
          </button>
        </div>
      </div>
    </div>

    <!-- Advantages Section -->
    <div class="advantages-background">
      <div class="advantages">
        <h2>КЛЮЧЕВЫЕ ПРЕИМУЩЕСТВА</h2>

        <div class="advantage-item">
          <h3>
            01 | Экспертное знание рынка
            <span>▼</span>
          </h3>
          <p>
            Мы предлагаем полный спектр услуг по дизайну и реализации готических
            интерьеров: от разработки концепции до подбора мебели и декора. Мы
            создадим для вас уникальное пространство, сочетающее в себе роскошь,
            таинственность и индивидуальность
          </p>
        </div>

        <div class="advantage-item">
          <h3>
            02 | Широкий выбор объектов
            <span>▼</span>
          </h3>
          <p>Описание преимущества 2...</p>
        </div>

        <div class="advantage-item">
          <h3>
            03 | Профессиональный опыт
            <span>▼</span>
          </h3>
          <p>Описание преимущества 3...</p>
        </div>

        <div class="advantage-item">
          <h3>
            04 | Индивидуальный подход
            <span>▼</span>
          </h3>
          <p>Описание преимущества 4...</p>
        </div>

        <div class="advantage-item">
          <h3>
            05 | Конфиденциальность
            <span>▼</span>
          </h3>
          <p>Описание преимущества 5...</p>
        </div>
      </div>
    </div>
    <div class="container">
      
  <h1>Изысканные пространства</h1>
  <div class="card-container">
    <?php foreach ($products as $product): ?>
      <div class="card">
        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
        <div class="card-content">
          <h2><?= htmlspecialchars($product['price']) ?>$</h2>
          <p><?= htmlspecialchars($product['description']) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Пагинация -->
  <div class="pagination">
    <a href="?page=1">Первая</a>
    <a href="?page=<?= max(1, $page - 1) ?>">Предыдущая</a>
    <a href="?page=<?= min($total_pages, $page + 1) ?>">Следующая</a>
    <a href="?page=<?= $total_pages ?>">Последняя</a>
  </div>
</div>
    <div class="faq" id="faq">
      <h2>Часто задаваемые вопросы</h2>

      <div class="faq-item">
        <button class="faq-btn">Что такое готический интерьер?</button>
        <div class="faq-content">
          <p>
            Готический интерьер сочетает элементы средневековой архитектуры с
            современными дизайнерскими решениями. Это стиль, который использует
            высокие потолки, арочные окна, темные цвета и уникальные
            декоративные элементы, создавая атмосферу мистики и элегантности.
          </p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-btn">
          Как долго длится процесс разработки дизайна?
        </button>
        <div class="faq-content">
          <p>
            Процесс разработки индивидуального дизайна может занять от
            нескольких недель до нескольких месяцев в зависимости от сложности
            проекта и ваших требований. Мы всегда стараемся учесть все пожелания
            клиента и предоставить оптимальный результат.
          </p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-btn">
          Какие материалы мы используем для готических интерьеров?
        </button>
        <div class="faq-content">
          <p>
            Мы используем только высококачественные материалы, которые
            соответствуют эстетике и долговечности готического стиля. Это могут
            быть натуральные камни, дерево, металл, витражи, текстуры,
            соответствующие историческим стандартам.
          </p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-btn">
          Можно ли заказать только отдельные элементы интерьера?
        </button>
        <div class="faq-content">
          <p>
            Да, мы предоставляем услуги как по полному проектированию интерьера,
            так и по созданию отдельных элементов, таких как мебель, декор,
            освещение и другие детали, которые могут быть интегрированы в уже
            существующий интерьер.
          </p>
        </div>
      </div>
    </div>

    <section class="hero-block">
      <div class="content">
        <h2>
          Оставьте заявку на звонок для того, что бы узнать все подробности и
          задать все интересующие вопросы
        </h2>
        <button class="cta-button" onclick="openModal()">
          Связаться с нами
        </button>
      </div>
    </section>
    <footer class="footer-block">
      <div class="footer-content">
        <!-- Логотип -->
        <div class="footer-logo">
          <h1>GOTHSET</h1>
        </div>

        <!-- Навигация -->
        <nav class="footer-nav">
          <a href="#about">О нас</a>
          <a href="#catalog">Каталог</a>
          <a href="#contacts">Контакты</a>
        </nav>

        <!-- Карта -->
        <div class="footer-map">
          <img src="src/img/map.png" alt="Карта" />
        </div>
      </div>
    </footer>

    <!-- Модальное окно -->
    <div id="contactModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h1>Связаться с нами</h1>
        <form method="POST" action="save_data.php">
          <input
            type="text"
            name="name"
            placeholder="Введите имя..."
            required
          />
          <input
            type="text"
            name="phone"
            placeholder="Введите номер телефона..."
            required
          />
          <button type="submit">ОСТАВИТЬ ЗАЯВКУ НА ЗВОНОК</button>
        </form>
      </div>
    </div>

    <script src="./script.js"></script>
    <script>
  // Открытие модального окна для контакта
  function openModal() {
    document.getElementById('contactModal').style.display = 'flex';
  }

  // Закрытие модального окна для контакта
  function closeModal() {
    document.getElementById('contactModal').style.display = 'none';
  }

  // Закрытие окна при клике вне области
  window.onclick = function(event) {
    const contactModal = document.getElementById('contactModal');
    if (event.target === contactModal) {
      closeModal();
    }

    const callModal = document.getElementById('callModal');
    if (event.target === callModal) {
      closeCallModal();
    }
  };

  // Открытие модального окна для звонков
  function openCallModal() {
    document.getElementById('callModal').style.display = 'flex';
  }

  // Закрытие модального окна для звонков
  function closeCallModal() {
    document.getElementById('callModal').style.display = 'none';
  }

  // Открытие/закрытие ответов в FAQ
  document.addEventListener('DOMContentLoaded', () => {
    const faqButtons = document.querySelectorAll('.faq-btn');
    faqButtons.forEach(button => {
      button.addEventListener('click', function() {
        const content = this.nextElementSibling;
        content.style.display = content.style.display === 'block' ? 'none' : 'block';
      });
    });
  });

  // Инициализация модальных окон
  document.addEventListener('DOMContentLoaded', () => {
    // Скрываем оба модальных окна при загрузке
    closeModal();
    closeCallModal();

    // Обработчик для кнопки закрытия модального окна контактов
    document.querySelector('#contactModal .close').onclick = closeModal;

    // Обработчик для кнопки закрытия модального окна звонков
    document.querySelector('#callModal .close').onclick = closeCallModal;
  });
</script>
  </body>
</html>
