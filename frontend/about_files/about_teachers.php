<?php
require_once '../../backend/config.php';

// Отримуємо всіх вчителів
$sql = "SELECT * FROM teachers";
$result = $pdo->query($sql);
$teachers = [];
if ($result) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $teachers[] = $row;
    }
}

// Мапінг категорій на українську мову
$categoryNames = [
    'administration' => 'Адміністрація закладу',
    'primary_teacher' => 'Учителі початкових класів',
    'language_and_literature' => 'Мовно-літературна освітня галузь',
    'mathematics' => 'Математична освітня галузь',
    'civil_and_historical' => 'Громадянська та історична освітня галузь',
    'natural_sciences' => 'Природнича освітня галузь',
    'technical_sciences' => 'Технологічна освітня галузь',
    'informatics' => 'Інформатична освітня галузь',
    'social_and_health' => 'Соціальна і здоров\'язбережувальна галузь',
    'arts' => 'Мистецька освітня галузь',
    'physical_education' => 'Фізкультурна освітня галузь'
];

// Функція для відображення вчителів за категоріями
function displayTeachersByCategory($category, $teachers, $categoryNames)
{
    $filteredTeachers = array_filter($teachers, function ($teacher) use ($category) {
        return $teacher[$category] == 1;
    });

    if (empty($filteredTeachers)) {
        return '';
    }

    // Отримуємо назву категорії на українській мові
    $categoryTitle = isset($categoryNames[$category]) ? $categoryNames[$category] : ucfirst(str_replace('_', ' ', $category));

    $html = "<h2 class='teachers-subtitle'>" . htmlspecialchars($categoryTitle) . "</h2>";
    $html .= "<div class='teachers-grid'>";

    foreach ($filteredTeachers as $teacher) {
        $html .= "<div class='teacher-card'>
                    <img src='" . htmlspecialchars($teacher['photo_path']) . "' alt='Фото вчителя'>
                    <div class='teacher-name'>" . htmlspecialchars($teacher['full_name']) . "</div>
                    <p>" . htmlspecialchars($teacher['position']) . "</p>
                  </div>";
    }

    $html .= "</div>";
    return $html;
}
?>

<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Хотинський опорний академічний ліцей</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../lalala.css">
    <script src="../blickFixer.js"></script>
</head>

<body>
    <!-- Основна навігаційна панель -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../index.html">
                <img src="../../assets/images/Logo_v2.svg" alt="Логотип" width="96" height="48" class="rounded">
                <div class="split-text ms-2 fw-bold">Хотинський опорний <br> академічний ліцей</div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button">
                            Про нас
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                            <li><a class="dropdown-item" href="about_general.html">Загальна інформація</a>
                            </li>
                            <li><a class="dropdown-item" href="about_teachers.php">Педагогічний
                                    колектив</a></li>
                            <li><a class="dropdown-item" href="about_governance.php">Органи громадського
                                    врядування</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="participantsDropdown"
                            role="button">
                            Учасникам освітнього процесу
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="participantsDropdown">
                            <li><a class="dropdown-item" href="../for_teacher.php">Педагогічним працівникам</a></li>
                            <li><a class="dropdown-item" href="../for_parents.php">Батькам</a></li>
                            <li><a class="dropdown-item" href="../for_students.php">Здобувачам освіти</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="../documents.php" id="documentsDropdown" role="button">
                            Документи
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="../news.html">Новини</a></li>

                    <!-- Кнопка для відкриття другого рівня навігації -->
                    <li class="nav-item">
                        <button class="btn btn-outline-light" id="toggleSecondaryNavbar">Доступність</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Другий рівень навігаційної панелі -->
    <div id="secondaryNavbar" class="bg-secondary text-white p-3 collapse">
        <div class="container d-flex flex-wrap justify-content-center">
            <button id="increase-font" class="btn btn-light m-1">+A</button>
            <button id="decrease-font" class="btn btn-light m-1">−A</button>
            <button id="reset-font" class="btn btn-light m-1">A</button>
            <button id="toggle-grayscale" class="btn btn-dark m-1">Чорно-білий режим</button>
            <button id="toggle-dyslexia-font" class="btn btn-warning m-1">Шрифт для людей з дислексією</button>
            <button id="theme-toggle" class="btn btn-outline-light m-1">🌙 Змінити тему</button>
        </div>
    </div>

    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="teachers-container">
            <h1 class="teachers-title">Наш колектив</h1>

            <!-- Адміністрація -->
            <?= displayTeachersByCategory('administration', $teachers, $categoryNames) ?>

            <!-- Учителі початкових класів -->
            <?= displayTeachersByCategory('primary_teacher', $teachers, $categoryNames) ?>

            <!-- Мовно-літературна освітня галузь -->
            <?= displayTeachersByCategory('language_and_literature', $teachers, $categoryNames) ?>

            <!-- Математична освітня галузь -->
            <?= displayTeachersByCategory('mathematics', $teachers, $categoryNames) ?>

            <!-- Громадянська та історична освітня галузь -->
            <?= displayTeachersByCategory('civil_and_historical', $teachers, $categoryNames) ?>

            <!-- Природнича освітня галузь -->
            <?= displayTeachersByCategory('natural_sciences', $teachers, $categoryNames) ?>

            <!-- Технічна освітня галузь -->
            <?= displayTeachersByCategory('technical_sciences', $teachers, $categoryNames) ?>

            <!-- Інформатична освітня галузь -->
            <?= displayTeachersByCategory('informatics', $teachers, $categoryNames) ?>

            <!-- Соціальна і здоров'язбережувальна галузь -->
            <?= displayTeachersByCategory('social_and_health', $teachers, $categoryNames) ?>

            <!-- Мистецька освітня галузь -->
            <?= displayTeachersByCategory('arts', $teachers, $categoryNames) ?>

            <!-- Фізкультурна освітня галузь -->
            <?= displayTeachersByCategory('physical_education', $teachers, $categoryNames) ?>
        </div>
    </div>



    <!-- Стрілочка повернення на верх сторінки -->
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Футер -->
    <footer class="footer bg-dark text-white py-4">
        <div class="container">
            <div class="supconteiner d-flex justify-content-around">
                <div class="col-md-6">
                    <h5>Контакти</h5>
                    <ul class="list-unstyled">
                        <li>
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span class="map-link"
                                data-address="вулиця Івана Франка, 8, Хотин, Чернівецька область, 60000">Місто Хотин,
                                вулиця Франка
                                І. 8/А</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope me-2"></i>
                            <span class="copy-text"
                                data-copy="khotynacademiclyceum@gmail.com">khotynacademiclyceum@gmail.com</span>
                        </li>
                        <li>
                            <i class="fas fa-phone me-2"></i>
                            <span class="copy-text" data-copy="+380660107072">+380 660 107 072</span>
                            <br> Директорка закладу Людмила Микитюк
                        </li>
                    </ul>
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                        Зв'яжіться з нами
                    </button>
                </div>

                <div class="col-md-6">
                    <h5>Графік роботи</h5>
                    <ul class="list-unstyled">
                        <li>Понеділок - П'ятниця: 8:00 - 18:15</li>
                        <li>Субота - Неділя: Вихідні</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Соціальні мережі</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://www.facebook.com/khotynacademiclyceum?locale=uk_UA"
                                class="text-white text-decoration-none social-link"><i
                                    class="fab fa-facebook me-2"></i>Facebook</a></li>
                        <li><a href="https://www.instagram.com/leaders_khotynacademiclyceum?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="
                                class="text-white text-decoration-none social-link"><i
                                    class="fab fa-instagram me-2"></i>Instagram</a></li>
                        <li><a href="https://www.tiktok.com/@leaders_khotynozzso?is_from_webapp=1&sender_device=pc"
                                class="text-white text-decoration-none social-link"><i
                                    class="fab fa-tiktok me-2"></i>Tik Tok</a></li>
                        <li><a href="https://www.youtube.com/@%D0%A5%D0%BE%D1%82%D0%B8%D0%BD%D1%81%D1%8C%D0%BA%D0%B8%D0%B9%D0%BE%D0%BF%D0%BE%D1%80%D0%BD%D0%B8%D0%B9%D0%B0%D0%BA%D0%B0%D0%B4%D0%B5%D0%BC%D1%96%D1%87%D0%BD%D0%B8%D0%B9%D0%BB%D1%96"
                                class="text-white text-decoration-none social-link"><i
                                    class="fab fa-youtube me-2"></i>YouTube</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>



    <!-- Модальне вікно -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Напишіть нам</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="https://api.web3forms.com/submit" method="POST">
                        <input type="hidden" name="access_key" value="6301ec0e-14c4-4e40-8ba8-74bec1b11524">
                        <div class="mb-3">
                            <label for="name" class="form-label">Ім'я</label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="Введіть Ваше ім'я..." autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required
                                placeholder="Введіть вашу електронну адресу..." autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="fa-phone" class="form-label">Номер телефону</label>
                            <input type="number" name="number" class="form-control" required
                                placeholder="Введіть номер телефону..." autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Повідомлення</label>
                            <textarea name="message" class="form-control" rows="3" required
                                placeholder="Введіть ваше повідомлення..." autocomplete="off"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Надіслати</button>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <script src="../scripts.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>