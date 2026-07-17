<?php
$cakeDescription = 'Hội Nghệ Thuật Nến';
$navPage = $page ?? null;
$isHome = ($navPage === 'home' || $navPage === null);
$isBooking = $this->request->getParam('controller') === 'Bookings' && $this->request->getParam('action') === 'add';
$isAbout = rtrim($this->request->getUri()->getPath(), '/') === '/pages/about';
$isVisit = rtrim($this->request->getUri()->getPath(), '/') === '/visit';
$isContact = rtrim($this->request->getUri()->getPath(), '/') === '/contact';
$user = $this->request->getAttribute('identity');
$isAdmin = $user && $user->role === 'admin';
$isAdminNav = $isAdmin || str_starts_with($this->request->getPath(), '/admin');
$adminNavItems = [
    ['label' => 'Bảng điều khiển', 'url' => '/admin'],
    ['label' => 'Người dùng', 'url' => '/admin/users'],
    ['label' => 'Hỏi đáp', 'url' => '/admin/enquiries'],
    ['label' => 'Giảng viên', 'url' => '/admin/teachers'],
    ['label' => 'Hội thảo', 'url' => '/admin/workshops'],
    ['label' => 'Vật liệu', 'url' => '/admin/materials'],
    ['label' => 'Câu hỏi thường gặp', 'url' => '/admin/faqs'],
    ['label' => 'Công ty', 'url' => '/admin/company'],
    ['label' => 'Đặt chỗ', 'url' => '/admin/bookings'],
    ['label' => 'Lịch giảng viên', 'url' => '/admin/teacher-availability'],
];

// Studio assistant (AI chat FAB): Home, About, Booking, FAQs only
$studioAssistantPath = rtrim($this->request->getUri()->getPath(), '/') ?: '/';
$showStudioAssistant = (
    ($this->request->getParam('controller') === 'Bookings' && $this->request->getParam('action') === 'add')
    || $studioAssistantPath === '/'
    || $studioAssistantPath === '/pages/about'
    || $studioAssistantPath === '/workshops'
    || $studioAssistantPath === '/visit'
    || $studioAssistantPath === '/contact'
    || $studioAssistantPath === '/faqs'
);
?>
<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <?= $this->Html->charset() ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $csrf = $this->request->getAttribute('csrfToken'); ?>
    <?php if ($csrf !== null): ?>
    <meta name="csrf-token" content="<?= h($csrf) ?>">
    <?php endif; ?>
    <meta name="description" content="Hội Nghệ Thuật Nến: các hội thảo sáng tạo cao cấp về gốm và đan. Hội thảo do chuyên gia dẫn dắt, nhóm nhỏ, bao gồm đầy đủ vật liệu.">

    <title><?= $cakeDescription ?>: <?= $this->fetch('title') ?></title>

    <?= $this->Html->meta('icon', '/favicon.svg', ['type' => 'image/svg+xml']) ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
                        script: ['"Dancing Script"', 'cursive'],
                    },
                    colors: {
                        primary: {
                            50:  '#f7f1f2',
                            100: '#ede0e3',
                            200: '#dcc3ca',
                            300: '#c598a5',
                            400: '#a96678',
                            500: '#8e4458',
                            600: '#752f3f',
                            700: '#5f2734',
                            800: '#4f202a',
                            900: '#3b181f',
                            950: '#1f0c10',
                        },
                        ink: {
                            950: '#0c0f0e',
                            900: '#141a18',
                            800: '#1c2421',
                        },
                        sage: {
                            50:  '#f4f6f2',
                            100: '#e4e9df',
                            400: '#8a9a7a',
                            500: '#6f8062',
                            600: '#5a6a50',
                        },
                        studio: {
                            ivory: '#f7f5f0',
                            stone: '#e8e4dc',
                            mist: '#efede8',
                        },
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(15, 23, 42, 0.08), 0 4px 6px -4px rgba(15, 23, 42, 0.06)',
                        'lift': '0 20px 50px -12px rgba(15, 23, 42, 0.15)',
                        'glow': '0 0 40px -10px rgba(117, 47, 63, 0.38)',
                        'nav': '0 1px 2px rgba(15, 23, 42, 0.04), inset 0 1px 0 rgba(255,255,255,0.6)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.85s ease-out forwards',
                        'fade-in-up': 'fadeInUp 0.7s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(8px)' },
                        },
                    },
                }
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let lastScrollTop = 0;
            const navbar = document.querySelector('[data-site-nav]');
            if (!navbar) return;

            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                if (scrollTop > lastScrollTop && scrollTop > 160) {
                    navbar.style.transform = 'translateY(-130%)';
                    navbar.style.opacity = '0';
                } else {
                    navbar.style.transform = 'translateY(0)';
                    navbar.style.opacity = '1';
                }
                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            });

            const btn = document.getElementById('mobile-menu-btn');
            const panel = document.getElementById('mobile-menu-panel');
            if (btn && panel) {
                btn.addEventListener('click', () => {
                    panel.classList.toggle('hidden');
                    const isOpen = !panel.classList.contains('hidden');
                    btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
                const closePanel = () => {
                    panel.classList.add('hidden');
                    btn.setAttribute('aria-expanded', 'false');
                };
                panel.querySelectorAll('a').forEach((a) => {
                    a.addEventListener('click', closePanel);
                });
                panel.querySelectorAll('button').forEach((b) => {
                    b.addEventListener('click', closePanel);
                });
            }

        });
    </script>

    <?= $this->Html->css(['site', 'flash-toast']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <?= $this->Html->script('flash-toast', ['defer' => true]) ?>
    <?= $this->Html->script('cookie-consent', ['defer' => true]) ?>
</head>

<body class="min-h-dvh bg-gradient-to-b from-studio-ivory via-neutral-50 to-studio-mist/40 text-ink-900 antialiased font-sans">

    <a href="#main-content" class="sr-only focus:fixed focus:left-4 focus:top-24 focus:z-[100] focus:inline-flex focus:h-auto focus:w-auto focus:overflow-visible focus:clip-auto focus:whitespace-normal focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-primary-600 focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-400">Bỏ qua nội dung</a>

    <header class="fixed inset-x-0 top-0 z-50 border-b border-neutral-200/70 bg-white/80 shadow-[0_1px_0_0_rgba(15,23,42,0.04)] backdrop-blur-xl supports-[backdrop-filter]:bg-white/70">
        <div data-site-nav class="transition-all duration-300 ease-out opacity-100">
            <nav class="mx-auto flex max-w-screen-2xl items-center justify-between gap-4 px-4 py-3 sm:px-3 sm:py-3.5 lg:px-4 md:min-h-[4.25rem]" aria-label="Điều hướng chính">
                <div class="flex shrink-0 items-center gap-2 min-w-0 md:gap-3">
                    <?= $this->Html->link(
                        '<span class="flex items-center gap-2 min-h-0 md:gap-2.5">'
                        . $this->Html->image('candlecraft-mark.svg', [
                            'alt' => '',
                            'class' => 'h-9 w-9 shrink-0 transition group-hover:opacity-90 md:h-10 md:w-10',
                            'width' => 40,
                            'height' => 40,
                        ])
                        . '<span class="font-script text-lg text-primary-700 tracking-wide leading-none md:text-xl">Hội Nghệ Thuật Nến</span>'
                        . '<span class="sr-only"> Trang chủ</span></span>',
                        '/',
                        ['class' => 'group flex items-center rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2', 'escape' => false]
                    ) ?>
                </div>

                <div class="hidden min-w-0 flex-1 items-center justify-center md:flex md:px-4">
                    <div class="flex flex-wrap items-center justify-center gap-2 lg:gap-3">
                        <div class="flex flex-wrap items-center justify-center gap-0.5 rounded-full border border-neutral-200/80 bg-white/70 p-1 shadow-nav backdrop-blur-sm">
                            <?php
                            $navOn = 'rounded-full px-3 py-2 text-sm font-semibold text-primary-900 bg-white shadow-sm ring-1 ring-neutral-200/60';
                            $navOff = 'rounded-full px-3 py-2 text-sm font-medium text-neutral-600 transition-colors duration-200 hover:bg-white hover:text-primary-800';
                            ?>
                            <?php if ($isAdminNav): ?>
                                <?= $this->Html->link('Trang web', '/', [
                                    'class' => $navOff,
                                ]) ?>
                                <?php foreach ($adminNavItems as $adminNavItem): ?>
                                    <?php
                                    $isCurrentAdminNavItem = $adminNavItem['url'] === '/admin'
                                        ? $this->getRequest()->getPath() === '/admin'
                                        : str_starts_with($this->getRequest()->getPath(), $adminNavItem['url']);
                                    ?>
                                    <?= $this->Html->link($adminNavItem['label'], $adminNavItem['url'], [
                                        'class' => $isCurrentAdminNavItem ? $navOn : $navOff,
                                    ]) ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?= $this->Html->link('Trang chủ', '/', [
                                    'class' => ($this->getRequest()->getPath() === '/' || $this->getRequest()->getParam('action') === 'home') ? $navOn : $navOff,
                                ]) ?>
                                <?= $this->Html->link('Về chúng tôi', '/pages/about', [
                                    'class' => ($navPage === 'about' || $this->getRequest()->getParam('action') === 'about' || $isAbout) ? $navOn : $navOff,
                                ]) ?>
                                <?= $this->Html->link('Hội thảo', '/workshops', [
                                    'class' => ($navPage === 'workshops' || $this->getRequest()->getPath() === '/workshops') ? $navOn : $navOff,
                                ]) ?>
                                <?= $this->Html->link('Thăm của bạn', '/visit', [
                                    'class' => ($navPage === 'visit' || $isVisit) ? $navOn : $navOff,
                                ]) ?>
                                <button
                                    type="button"
                                    onclick="openContactModal()"
                                    class="<?= $isContact ? $navOn : 'rounded-full px-3 py-2 text-sm font-medium text-neutral-600 transition-colors duration-200 hover:bg-white/90 hover:text-primary-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white' ?>"
                                    aria-haspopup="dialog"
                                >
                                    Liên hệ
                                </button>
                            <?php endif; ?>
                            <?php if ($user && $user->role === 'teacher'): ?>
                                <?= $this->Html->link('Trung tâm giảng viên', '/teacher', [
                                    'class' => str_starts_with($this->getRequest()->getPath(), '/teacher') ? $navOn : $navOff,
                                ]) ?>
                            <?php elseif ($user && $user->role !== 'admin'): ?>
                                <?= $this->Html->link('Tài khoản', '/users/profile', [
                                    'class' => $this->getRequest()->getPath() === '/users/profile' ? $navOn : $navOff,
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-2 md:gap-2.5">
                    <?php if ($user): ?>
                        <span class="hidden max-w-[11rem] truncate text-xs text-neutral-500 xl:inline">
                            <?= h($user->email) ?>
                        </span>
                        <?= $this->Html->link('Đăng xuất', '/users/logout', [
                            'class' => 'hidden sm:inline-flex items-center rounded-full border border-neutral-200/90 bg-white px-3.5 py-2 text-sm font-semibold text-neutral-800 shadow-sm transition-colors duration-200 hover:border-neutral-300 hover:bg-neutral-50 md:px-4',
                        ]) ?>
                    <?php else: ?>
                        <?= $this->Html->link('Đăng nhập', '/pages/login', [
                            'class' => 'hidden sm:inline-flex items-center rounded-full border border-neutral-200/90 bg-white px-3.5 py-2 text-sm font-semibold text-neutral-800 shadow-sm transition-colors duration-200 hover:border-primary-200/80 hover:bg-white md:px-4',
                        ]) ?>
                    <?php endif; ?>
                    <?= $this->Html->link(
                        'Đặt ngay',
                        '/booking',
                        [
                            'class' => 'inline-flex items-center rounded-full bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-primary-900/10 transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2 md:px-5'
                                . ($isBooking ? ' ring-2 ring-primary-200 ring-offset-2 ring-offset-white' : ''),
                        ]
                    ) ?>
                    <button type="button" id="mobile-menu-btn" class="md:hidden inline-flex items-center justify-center rounded-xl border border-neutral-200/90 bg-white p-2.5 text-neutral-700 shadow-sm transition hover:bg-neutral-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400" aria-expanded="false" aria-controls="mobile-menu-panel" aria-label="Mở menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </nav>
        </div>

        <div id="mobile-menu-panel" class="hidden border-t border-neutral-200/80 bg-gradient-to-b from-white to-neutral-50/95 px-4 py-5 shadow-inner md:hidden">
            <div class="mx-auto flex max-w-lg flex-col gap-1 text-[0.9375rem] font-medium">
                <?php if ($isAdminNav): ?>
                    <?= $this->Html->link('Trang web', '/', ['class' => 'rounded-xl px-3 py-3 text-neutral-800 hover:bg-studio-mist']) ?>
                <?php else: ?>
                    <?= $this->Html->link('Trang chủ', '/', ['class' => 'rounded-xl px-3 py-3 hover:bg-studio-mist ' . ($isHome ? 'text-primary-700 bg-primary-50/80' : 'text-neutral-800')]) ?>
                    <?= $this->Html->link('Về chúng tôi', '/pages/about', ['class' => 'rounded-xl px-3 py-3 hover:bg-studio-mist ' . ($navPage === 'about' || $isAbout ? 'text-primary-700 bg-primary-50/80 font-semibold' : 'text-neutral-800')]) ?>
                    <?= $this->Html->link('Hội thảo', '/workshops', ['class' => 'rounded-xl px-3 py-3 hover:bg-studio-mist ' . ($navPage === 'workshops' || $this->getRequest()->getPath() === '/workshops' ? 'text-primary-700 bg-primary-50/80' : 'text-neutral-800')]) ?>
                    <?= $this->Html->link('Thăm của bạn', '/visit', ['class' => 'rounded-xl px-3 py-3 hover:bg-studio-mist ' . ($navPage === 'visit' || $isVisit ? 'text-primary-700 bg-primary-50/80 font-semibold' : 'text-neutral-800')]) ?>
                    <button type="button" onclick="openContactModal()" class="w-full rounded-xl px-3 py-3 text-left hover:bg-studio-mist <?= $isContact ? 'bg-primary-50/80 font-semibold text-primary-700' : 'text-neutral-800' ?>" aria-haspopup="dialog">Liên hệ</button>
                    <?= $this->Html->link('Đặt ngay', '/booking', ['class' => 'rounded-xl px-3 py-3 ' . ($isBooking ? 'bg-primary-100 font-semibold text-primary-800' : 'text-primary-700 hover:bg-primary-50')]) ?>
                    <?php if ($user && $user->role === 'teacher'): ?>
                        <?= $this->Html->link('Trung tâm giảng viên', '/teacher', ['class' => 'rounded-xl px-3 py-3 ' . (str_starts_with($this->getRequest()->getPath(), '/teacher') ? 'text-primary-700 bg-primary-50/80 font-semibold' : 'text-neutral-800 hover:bg-studio-mist')]) ?>
                    <?php elseif ($user): ?>
                        <?= $this->Html->link('Tài khoản', '/users/profile', ['class' => 'rounded-xl px-3 py-3 text-neutral-800 hover:bg-studio-mist']) ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($isAdmin): ?>
                    <span class="px-3 pt-2 text-xs font-semibold uppercase tracking-[0.05em] text-neutral-500">Quản trị viên</span>
                    <?php foreach ($adminNavItems as $adminNavItem): ?>
                        <?= $this->Html->link($adminNavItem['label'], $adminNavItem['url'], ['class' => 'rounded-xl px-3 py-2.5 pl-5 text-neutral-800 hover:bg-studio-mist']) ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="mt-2 flex flex-col gap-2 border-t border-neutral-200 pt-3">
                    <?php if ($user): ?>
                        <span class="truncate px-3 text-sm text-neutral-600"><?= h($user->email) ?></span>
                        <?= $this->Html->link('Đăng xuất', '/users/logout', ['class' => 'inline-flex justify-center rounded-full bg-primary-600 px-4 py-3 text-center text-base font-semibold text-white']) ?>
                    <?php else: ?>
                        <?= $this->Html->link('Đăng nhập', '/pages/login', ['class' => 'inline-flex justify-center rounded-full border border-neutral-300 px-4 py-3 text-center text-base font-semibold text-neutral-800']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div id="flash-toast-region" class="flash-toast-region" aria-live="polite" aria-relevant="additions" aria-label="Thông báo">
        <?= $this->Flash->render() ?>
    </div>

    <?php
    $portalPath = rtrim($this->getRequest()->getPath(), '/') ?: '/';
    $isTeacherPortal = str_starts_with($portalPath, '/teacher');
    ?>
    <main id="main-content" class="min-h-screen animate-fade-in pt-[4.75rem] sm:pt-20 md:pt-[5.25rem]">
        <?php if ($isTeacherPortal): ?>
            <div class="mx-auto flex w-full max-w-screen-2xl flex-col gap-4 px-4 pb-16 sm:px-3 lg:flex-row lg:gap-5 lg:px-4 lg:pb-20">
                <?= $this->element('teacher_sidebar') ?>
                <div class="min-w-0 flex-1 lg:pt-1">
                    <?= $this->fetch('content') ?>
                </div>
            </div>
        <?php else: ?>
            <?= $this->fetch('content') ?>
        <?php endif; ?>
    </main>

    <?= $this->element('contact_modal') ?>
    <?= $this->element('legal_modals') ?>
    <?= $this->element('cookie_consent') ?>
    <?php if (!empty($showStudioAssistant)) : ?>
        <?= $this->element('studio_assistant') ?>
    <?php endif; ?>

</body>
</html>

