<?php
// Database connection and settings
require_once 'config.php';
require_once './lib/lib.php';
require_once './lib/settings_helpers.php';

// Load settings
$heroSettings = getHeroSettings();
$productSettings = getProductSettings();
$contactSettings = getContactSettings();
$socialSettings = getSocialSettings();
?>

<!DOCTYPE html>
<html lang="az">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Unipos Az - Restoranlar üçün müasir POS sistemi və QR Menu həlləri. Biznesinizi rəqəmsallaşdırın!">
    <meta name="keywords" content="POS sistemi, QR Menu, restoran, Azərbaycan, rəqəmsal həllər">
    <meta name="author" content="Unipos Az">
    <title>Unipos Az - POS Sistemi və QR Menu Həlləri</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Header & Navigation -->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="#" class="nav__logo">
                <img src="images/unipos_logo.png" alt="Unipos Az Logo" class="nav__logo-img">
                <span>Unipos Az</span>
            </a>

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="#home" class="nav__link active-link">Ana Səhifə</a>
                    </li>
                    <li class="nav__item">
                        <a href="#products" class="nav__link">Məhsullar</a>
                    </li>
                    <li class="nav__item">
                        <a href="#features" class="nav__link">Xüsusiyyətlər</a>
                    </li>
                    <li class="nav__item">
                        <a href="#about" class="nav__link">Haqqımızda</a>
                    </li>
                    <li class="nav__item">
                        <a href="#contact" class="nav__link">Əlaqə</a>
                    </li>
                </ul>
                <div class="nav__close" id="nav-close">
                    <i class="fas fa-times"></i>
                </div>
            </div>

            <div class="nav__toggle" id="nav-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main">
        <!-- Hero Section -->
        <section class="hero section" id="home">
            <div class="hero__container container">
                <div class="hero__content">
                    <h1 class="hero__title">
                        <?= htmlspecialchars($heroSettings['hero_title'] ?? 'Restoranınızı Rəqəmsallaşdırın') ?>
                    </h1>
                    <p class="hero__description">
                        <?= htmlspecialchars($heroSettings['hero_description'] ?? 'Müasir POS sistemi və QR Menu həlləri ilə biznesinizi növbəti səviyyəyə çıxarın. Sürətli, etibarlı və asan istifadə.') ?>
                    </p>
                    <div class="hero__buttons">
                        <a href="#contact" class="button button--primary">
                            <i class="fas fa-rocket"></i>
                            <span>İndi Başlayın</span>
                        </a>
                        <a href="#products" class="button button--secondary">
                            <i class="fas fa-play-circle"></i>
                            <span>Məhsullar</span>
                        </a>
                    </div>
                    <div class="hero__stats">
                        <div class="stat">
                            <h3 class="stat__number"><?= htmlspecialchars($heroSettings['hero_stat_1_number'] ?? '500+') ?></h3>
                            <p class="stat__label"><?= htmlspecialchars($heroSettings['hero_stat_1_label'] ?? 'Müştəri') ?></p>
                        </div>
                        <div class="stat">
                            <h3 class="stat__number"><?= htmlspecialchars($heroSettings['hero_stat_2_number'] ?? '99.9%') ?></h3>
                            <p class="stat__label"><?= htmlspecialchars($heroSettings['hero_stat_2_label'] ?? 'Uptime') ?></p>
                        </div>
                        <div class="stat">
                            <h3 class="stat__number"><?= htmlspecialchars($heroSettings['hero_stat_3_number'] ?? '24/7') ?></h3>
                            <p class="stat__label"><?= htmlspecialchars($heroSettings['hero_stat_3_label'] ?? 'Dəstək') ?></p>
                        </div>
                    </div>
                </div>
                <!-- <div class="hero__image">
                    <div class="hero__card">
                        <i class="fas fa-chart-line"></i>
                        <h4>Real vaxt analitika</h4>
                    </div>
                </div> -->
            </div>
        </section>

        <!-- Products Section -->
        <section class="products section" id="products">
            <div class="container">
                <div class="section__header">
                    <h2 class="section__title">Məhsullarımız</h2>
                    <p class="section__subtitle">Biznesiniz üçün ən yaxşı həllər</p>
                </div>

                <div class="products__container">
                    <!-- POS System -->
                    <div class="product__card">
                        <div class="product__icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <h3 class="product__title"><?= htmlspecialchars($productSettings['product_pos_title'] ?? 'POS Sistemi') ?></h3>
                        <p class="product__description">
                            <?= htmlspecialchars($productSettings['product_pos_description'] ?? 'Tam funksional POS sistemi ilə satışlarınızı idarə edin, anbar və hesabatları izləyin.') ?>
                        </p>
                        <ul class="product__features">
                            <li><i class="fas fa-check-circle"></i> Sürətli ödəniş prosesi</li>
                            <li><i class="fas fa-check-circle"></i> Anbar idarəetməsi</li>
                            <li><i class="fas fa-check-circle"></i> Detallı hesabatlar</li>
                            <li><i class="fas fa-check-circle"></i> Çoxlu ödəniş üsulları</li>
                            <li><i class="fas fa-check-circle"></i> Oflayn rejimi</li>
                        </ul>
                        <a href="#contact" class="button button--outline">
                            <span>Daha ətraflı</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <!-- QR Menu -->
                    <div class="product__card product__card--featured">
                        <div class="product__badge">Populyar</div>
                        <div class="product__icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h3 class="product__title"><?= htmlspecialchars($productSettings['product_qr_title'] ?? 'QR Menu') ?></h3>
                        <p class="product__description">
                            <?= htmlspecialchars($productSettings['product_qr_description'] ?? 'Müştəriləriniz üçün əlçatan, interaktiv və müasir rəqəmsal menyu həlli.') ?>
                        </p>
                        <ul class="product__features">
                            <li><i class="fas fa-check-circle"></i> Təmasız menyu</li>
                            <li><i class="fas fa-check-circle"></i> Real vaxt yeniləmələr</li>
                            <li><i class="fas fa-check-circle"></i> Çox dilli dəstək</li>
                            <li><i class="fas fa-check-circle"></i> Foto və təsvirlərlə</li>
                            <li><i class="fas fa-check-circle"></i> Sifariş inteqrasiyası</li>
                        </ul>
                        <a href="#contact" class="button button--primary">
                            <span>Daha ətraflı</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Integration -->
                    <div class="product__card">
                        <div class="product__icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3 class="product__title"><?= htmlspecialchars($productSettings['product_integration_title'] ?? 'Tam İnteqrasiya') ?></h3>
                        <p class="product__description">
                            <?= htmlspecialchars($productSettings['product_integration_description'] ?? 'POS və QR Menu sistemləri birlikdə tam avtomatlaşdırılmış həll yaradır.') ?>
                        </p>
                        <ul class="product__features">
                            <li><i class="fas fa-check-circle"></i> Vahid idarəetmə paneli</li>
                            <li><i class="fas fa-check-circle"></i> Avtomatik sinxronizasiya</li>
                            <li><i class="fas fa-check-circle"></i> Mərkəzləşdirilmiş data</li>
                            <li><i class="fas fa-check-circle"></i> Çoxlu filial dəstəyi</li>
                            <li><i class="fas fa-check-circle"></i> API inteqrasiyası</li>
                        </ul>
                        <a href="#contact" class="button button--outline">
                            <span>Daha ətraflı</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features section" id="features">
            <div class="container">
                <div class="section__header">
                    <h2 class="section__title">Niyə Unipos Az?</h2>
                    <p class="section__subtitle">Sizə təklif etdiyimiz üstünlüklər</p>
                </div>

                <div class="features__container">
                    <div class="feature__item">
                        <div class="feature__icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="feature__title">Yüksək Sürət</h3>
                        <p class="feature__description">
                            Optimizasiya olunmuş sistem ilə saniyələr içində əməliyyatları həyata keçirin.
                        </p>
                    </div>

                    <div class="feature__item">
                        <div class="feature__icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature__title">Təhlükəsizlik</h3>
                        <p class="feature__description">
                            Bank səviyyəsində şifrələmə ilə məlumatlarınız tam qorunur.
                        </p>
                    </div>

                    <div class="feature__item">
                        <div class="feature__icon">
                            <i class="fas fa-cloud"></i>
                        </div>
                        <h3 class="feature__title">Cloud Teknologiya</h3>
                        <p class="feature__description">
                            İstənilən yerdən istənilən cihazla sistemə daxil olun.
                        </p>
                    </div>

                    <div class="feature__item">
                        <div class="feature__icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="feature__title">24/7 Dəstək</h3>
                        <p class="feature__description">
                            Hər zaman sizin üçün hazırıq. Texniki dəstək komandamız daim xidmətinizdədir.
                        </p>
                    </div>

                    <div class="feature__item">
                        <div class="feature__icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature__title">Mobil Uyğunluq</h3>
                        <p class="feature__description">
                            Bütün cihazlarda mükəmməl işləyən responsive dizayn.
                        </p>
                    </div>

                    <div class="feature__item">
                        <div class="feature__icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3 class="feature__title">Analitika</h3>
                        <p class="feature__description">
                            Detallı hesabatlar və analitika ilə biznesinizi daha yaxşı başa düşün.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about section" id="about">
            <div class="container">
                <div class="about__container">
                    <div class="about__content">
                        <h2 class="section__title">Haqqımızda</h2>
                        <p class="about__text">
                            Unipos Az olaraq, biz Azərbaycanda restoran və kafe bizneslərini rəqəmsallaşdırmaq missiyası
                            ilə
                            fəaliyyət göstəririk. Müasir texnologiyalardan istifadə edərək, biznes sahiblərinə işlərini
                            daha effektiv idarə etməyə kömək edirik.
                        </p>
                        <p class="about__text">
                            Komandamız təcrübəli proqramçı, dizayner və biznes analitiklərindən ibarətdir. Hər bir
                            müştərimizə
                            fərdi yanaşma ilə ən uyğun həlləri təqdim edirik.
                        </p>
                        <div class="about__values">
                            <div class="value__item">
                                <i class="fas fa-crown"></i>
                                <h4>Missiyamız</h4>
                                <p>Restoran bizneslərini müasir texnologiya ilə təmin etmək</p>
                            </div>
                            <div class="value__item">
                                <i class="fas fa-eye"></i>
                                <h4>Vizyonumuz</h4>
                                <p>Azərbaycanda lider rəqəmsal həll provayderi olmaq</p>
                            </div>
                        </div>
                    </div>
                    <div class="about__image">
                        <div class="about__card">
                            <i class="fas fa-users"></i>
                            <p>Professional komanda</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact section" id="contact">
            <div class="container">
                <div class="section__header">
                    <h2 class="section__title">Bizimlə Əlaqə</h2>
                    <p class="section__subtitle">Suallarınız var? Bizimlə əlaqə saxlayın!</p>
                </div>

                <div class="contact__container">
                    <div class="contact__info">
                        <div class="contact__item">
                            <div class="contact__icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact__details">
                                <h4>Telefon</h4>
                                <p><?= htmlspecialchars($contactSettings['contact_phone'] ?? '+994 XX XXX XX XX') ?></p>
                            </div>
                        </div>

                        <div class="contact__item">
                            <div class="contact__icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact__details">
                                <h4>Email</h4>
                                <p><?= htmlspecialchars($contactSettings['contact_email'] ?? 'info@unipos.az') ?></p>
                            </div>
                        </div>

                        <div class="contact__item">
                            <div class="contact__icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact__details">
                                <h4>Ünvan</h4>
                                <p><?= htmlspecialchars($contactSettings['contact_address'] ?? 'Bakı, Azərbaycan') ?></p>
                            </div>
                        </div>

                        <div class="contact__social">
                            <h4>Sosial Şəbəkələr</h4>
                            <div class="social__links">
                                <a href="<?= htmlspecialchars($socialSettings['social_facebook'] ?? '#') ?>" class="social__link" <?= ($socialSettings['social_facebook'] ?? '#') != '#' ? 'target="_blank"' : '' ?>>
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="<?= htmlspecialchars($socialSettings['social_instagram'] ?? '#') ?>" class="social__link" <?= ($socialSettings['social_instagram'] ?? '#') != '#' ? 'target="_blank"' : '' ?>>
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="<?= htmlspecialchars($socialSettings['social_linkedin'] ?? '#') ?>" class="social__link" <?= ($socialSettings['social_linkedin'] ?? '#') != '#' ? 'target="_blank"' : '' ?>>
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="<?= htmlspecialchars($socialSettings['social_whatsapp'] ?? '#') ?>" class="social__link" <?= ($socialSettings['social_whatsapp'] ?? '#') != '#' ? 'target="_blank"' : '' ?>>
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <form class="contact__form" id="contact-form">
                        <div class="form__group">
                            <input type="text" class="form__input" id="name" name="name" placeholder="Ad Soyad"
                                required>
                            <label for="name" class="form__label">Ad Soyad</label>
                        </div>

                        <div class="form__group">
                            <input type="email" class="form__input" id="email" name="email" placeholder="Email"
                                required>
                            <label for="email" class="form__label">Email</label>
                        </div>

                        <div class="form__group">
                            <input type="tel" class="form__input" id="phone" name="phone" placeholder="Telefon"
                                required>
                            <label for="phone" class="form__label">Telefon</label>
                        </div>

                        <div class="form__group">
                            <select class="form__input" id="subject" name="subject" required>
                                <option value="">Mövzu seçin</option>
                                <option value="pos">POS Sistemi</option>
                                <option value="qr">QR Menu</option>
                                <option value="integration">Tam İnteqrasiya</option>
                                <option value="support">Texniki Dəstək</option>
                                <option value="other">Digər</option>
                            </select>
                            <label for="subject" class="form__label">Mövzu</label>
                        </div>

                        <div class="form__group form__group--full">
                            <textarea class="form__input" id="message" name="message" rows="5" placeholder="Mesajınız"
                                required></textarea>
                            <label for="message" class="form__label">Mesajınız</label>
                        </div>

                        <button type="submit" class="button button--primary button--full">
                            <i class="fas fa-paper-plane"></i>
                            <span>Göndər</span>
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__container">
                <div class="footer__content">
                    <a href="#" class="footer__logo">
                        <img src="images/unipos_logo.png" alt="Unipos Az Logo" class="footer__logo-img">
                        <span>Unipos Az</span>
                    </a>
                    <p class="footer__description">
                        Restoranlar üçün müasir POS sistemi və QR Menu həlləri.
                        Biznesinizi rəqəmsallaşdırın!
                    </p>
                </div>

                <div class="footer__links">
                    <h4 class="footer__title">Məhsullar</h4>
                    <ul class="footer__list">
                        <li><a href="#products">POS Sistemi</a></li>
                        <li><a href="#products">QR Menu</a></li>
                        <li><a href="#products">İnteqrasiya</a></li>
                    </ul>
                </div>

                <div class="footer__links">
                    <h4 class="footer__title">Şirkət</h4>
                    <ul class="footer__list">
                        <li><a href="#about">Haqqımızda</a></li>
                        <li><a href="#features">Xüsusiyyətlər</a></li>
                        <li><a href="#contact">Əlaqə</a></li>
                    </ul>
                </div>

                <div class="footer__links">
                    <h4 class="footer__title">Dəstək</h4>
                    <ul class="footer__list">
                        <li><a href="#contact">Texniki Dəstək</a></li>
                        <li><a href="#">Sənədlər</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer__bottom">
                <p class="footer__copy">
                    &copy; 2024 Unipos Az. Bütün hüquqlar qorunur.
                </p>
                <div class="footer__social">
                    <a href="<?= htmlspecialchars($socialSettings['social_facebook'] ?? '#') ?>" class="footer__social-link" <?= ($socialSettings['social_facebook'] ?? '#') != '#' ? 'target="_blank"' : '' ?>>
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="<?= htmlspecialchars($socialSettings['social_instagram'] ?? '#') ?>" class="footer__social-link" <?= ($socialSettings['social_instagram'] ?? '#') != '#' ? 'target="_blank"' : '' ?>>
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="<?= htmlspecialchars($socialSettings['social_linkedin'] ?? '#') ?>" class="footer__social-link" <?= ($socialSettings['social_linkedin'] ?? '#') != '#' ? 'target="_blank"' : '' ?>>
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll Up Button -->
    <a href="#" class="scrollup" id="scroll-up">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Custom JavaScript -->
    <script src="js/main.js"></script>
</body>

</html>