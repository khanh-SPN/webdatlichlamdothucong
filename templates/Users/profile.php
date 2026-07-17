<?php
$this->assign('title', 'My Account');
$announcementsForCustomer = $announcementsForCustomer ?? [];
$user = $this->request->getAttribute('identity'); // Giả sử lấy từ auth
$displayName = $customer->name ?? $user->name ?? (isset($user->email) ? explode('@', $user->email)[0] : null) ?? 'Maker';
$memberSince = null;
if (!empty($user->created) && $user->created instanceof \DateTimeInterface) {
    $memberSince = $user->created->format('M Y');
}
?>

<main class="min-h-screen flex items-center justify-center bg-gradient-to-br from-neutral-50 via-primary-50 to-neutral-50 py-3 px-4">
    <div class="w-full max-w-2xl animate-fade-in">
        <!-- Profile card với glassmorphism -->
        <div class="bg-white/75 backdrop-blur-xl rounded-2xl shadow-xl border border-neutral-200/40 overflow-hidden p-3 md:p-4">
            <!-- Header: Avatar + Welcome -->
            <div class="text-center mb-3">
                <div class="relative inline-block mb-4">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden border-3 border-primary-200 shadow-lg mx-auto bg-neutral-100 flex items-center justify-center">
                        <!-- Avatar placeholder (có thể thay bằng $user->avatar nếu có) -->
                        <span class="text-xl md:text-lg font-serif text-primary-500">
                            <?= strtoupper(substr((string)$displayName, 0, 1)) ?>
                        </span>
                    </div>
                    <!-- Optional edit avatar button -->
                    <button class="absolute bottom-1 right-1 bg-primary-500 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-md hover:bg-primary-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </button>
                </div>

                <h1 class="text-lg md:text-xl font-serif font-bold text-neutral-900 mb-2">
                    My Account
                </h1>
                <p class="text-base text-neutral-600 font-serif">
                    Hello, <?= h($displayName) ?>! Manage your personal details below.
                </p>

                <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs font-medium text-neutral-500">
                    <?php if (!empty($user->email)): ?>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1.5 ring-1 ring-neutral-200/70">
                            <span class="text-neutral-400">Email</span>
                            <span class="text-neutral-800"><?= h($user->email) ?></span>
                        </span>
                    <?php endif; ?>
                    <?php if ($memberSince): ?>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1.5 ring-1 ring-neutral-200/70">
                            <span class="text-neutral-400">Member since</span>
                            <span class="text-neutral-800"><?= h($memberSince) ?></span>
                        </span>
                    <?php endif; ?>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1.5 ring-1 ring-neutral-200/70">
                        <span class="text-neutral-400">Bookings</span>
                        <span class="text-neutral-800"><?= isset($bookings) ? count($bookings) : 0 ?></span>
                    </span>
                </div>

                <div class="mt-3 flex flex-wrap items-center justify-center gap-2">
                    <?= $this->Html->link('New booking', ['controller' => 'Bookings', 'action' => 'add'], [
                        'class' => 'inline-flex items-center justify-center rounded-full bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800 transition'
                    ]) ?>
                    <?= $this->Html->link('Workshops', '/workshops', [
                        'class' => 'inline-flex items-center justify-center rounded-full border border-neutral-300 bg-white px-4 py-2 text-sm font-semibold text-neutral-800 hover:bg-neutral-50 transition'
                    ]) ?>
                    <?= $this->Html->link('Sign out', ['controller' => 'Users', 'action' => 'logout'], [
                        'class' => 'inline-flex items-center justify-center rounded-full border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50 transition'
                    ]) ?>
                </div>
            </div>

            <?php if (!empty($announcementsForCustomer)): ?>
                <section class="mb-4 border-b border-neutral-200/60 pb-8" aria-labelledby="instructor-announcements-heading">
                    <h2 id="instructor-announcements-heading" class="font-serif text-xl font-bold text-neutral-900">
                        Messages from your instructors
                    </h2>
                    <p class="mt-2 text-sm text-neutral-600">Announcements from teachers whose workshops you have booked.</p>
                    <ul class="mt-4 space-y-3 text-left">
                        <?php foreach ($announcementsForCustomer as $ann): ?>
                            <li class="rounded-xl border border-primary-100/80 bg-primary-50/40 px-4 py-3">
                                <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-neutral-600">
                                    <span class="font-semibold text-primary-900"><?= h($ann->teacher->name ?? 'Instructor') ?></span>
                                    <span><?= h($ann->sent_at?->format('j M Y, H:i') ?? '') ?></span>
                                </div>
                                <?php if ($ann->workshop_id !== null && !empty($ann->workshop)): ?>
                                    <p class="mt-1 text-xs text-neutral-500">Re: <?= h($ann->workshop->workshop_name) ?></p>
                                <?php endif; ?>
                                <p class="mt-2 whitespace-pre-wrap text-sm text-neutral-800"><?= h($ann->body) ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>

            <section aria-labelledby="profile-details-heading">
                <h2 id="profile-details-heading" class="sr-only">Profile details</h2>
                <?= $this->Form->create($customer, ['class' => 'space-y-3']) ?>

                <!-- Full Name -->
                <div class="relative">
                    <?= $this->Form->text('name', [
                        'placeholder' => ' ',
                        'class' => 'peer w-full px-4 py-3 bg-neutral-50/50 border border-neutral-300 rounded-lg text-neutral-900 placeholder-transparent focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-all duration-300',
                        'value' => $customer->name ?? $user->name ?? ''
                    ]) ?>
                    <label class="absolute left-5 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                        Full Name
                    </label>
                </div>

                <!-- Phone -->
                <div class="relative">
                    <?= $this->Form->text('phone', [
                        'placeholder' => ' ',
                        'class' => 'peer w-full px-4 py-3 bg-neutral-50/50 border border-neutral-300 rounded-lg text-neutral-900 placeholder-transparent focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-all duration-300',
                        'value' => $customer->phone ?? $user->phone ?? ''
                    ]) ?>
                    <label class="absolute left-4 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                        Phone Number
                    </label>
                </div>

                <!-- Address (Textarea) -->
                <div class="relative">
                    <?= $this->Form->textarea('address', [
                        'placeholder' => ' ',
                        'class' => 'peer w-full px-4 py-3 bg-neutral-50/50 border border-neutral-300 rounded-lg text-neutral-900 placeholder-transparent focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-all duration-300 min-h-[100px] resize-y',
                        'value' => $customer->address ?? $user->address ?? ''
                    ]) ?>
                    <label class="absolute left-4 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                        Address
                    </label>
                </div>

                <!-- Submit -->
                <div class="text-center">
                    <?= $this->Form->button('Update Profile', [
                        'class' => 'inline-flex items-center px-4 py-3 text-base font-semibold rounded-full bg-primary-500 text-white hover:bg-primary-600 transition-all duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-300'
                    ]) ?>
                </div>

                <?= $this->Form->end() ?>
            </section>

            <!-- Bookings -->
            <section class="mt-5 border-t border-neutral-200/60 pt-8" aria-labelledby="account-bookings-heading">
                <div class="flex flex-col gap-2">
                    <h2 id="account-bookings-heading" class="font-serif text-xl md:text-lg font-bold text-neutral-900">
                        Past bookings
                    </h2>
                    <p class="text-neutral-600 font-serif">
                        Track status, pay open balances, or cancel pending requests when eligible.
                    </p>
                </div>

                <?php if (empty($bookingGroups)): ?>
                    <div class="mt-4 rounded-xl border border-neutral-200/70 bg-white/70 p-3 text-center">
                        <p class="font-serif font-semibold text-neutral-900">No bookings yet</p>
                        <p class="mt-2 text-sm text-neutral-600">Once you've booked a workshop, it'll appear here for quick rebooking.</p>
                        <div class="mt-5">
                            <?= $this->Html->link('Book a workshop', ['controller' => 'Bookings', 'action' => 'add'], [
                                'class' => 'inline-flex items-center justify-center rounded-full bg-primary-600 px-3 py-3 text-sm font-semibold text-white hover:bg-primary-700 transition'
                            ]) ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="mt-3 grid gap-4">
                        <?php foreach ($bookingGroups as $idx => $group): ?>
                            <?php
                            $count = count($group['bookings']);
                            $payPill = $group['paymentStatus'] === 'paid'
                                ? 'bg-primary-50 text-primary-900 ring-primary-100'
                                : 'bg-orange-50 text-orange-900 ring-orange-100';
                            $groupId = 'group-' . $idx;
                            ?>
                            <article class="rounded-2xl border border-neutral-200/70 bg-white/70 p-3 shadow-sm">
                                <!-- Summary Header -->
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="font-serif text-lg font-semibold text-neutral-900">
                                            <?= $count ?> <?= $count === 1 ? 'Workshop' : 'Workshops' ?>
                                            <?php if ($group['discountPercent'] > 0): ?>
                                                <span class="text-sm font-normal text-green-600">(<?= $group['discountPercent'] ?>% off)</span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="mt-1 text-sm text-neutral-600">
                                            Booked <?= $group['created'] instanceof \DateTimeInterface ? $group['created']->format('D, j M Y') : h((string)$group['created']) ?>
                                        </p>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 <?= h($payPill) ?>">
                                            <?= $group['paymentStatus'] === 'paid' ? 'Paid' : 'Unpaid' ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Price Summary -->
                                <div class="mt-4 flex items-center justify-between rounded-xl bg-neutral-50 px-4 py-3">
                                    <div>
                                        <?php if ($group['discountAmount'] > 0): ?>
                                            <p class="text-sm text-neutral-500 line-through">$<?= number_format($group['totalPrice']) ?></p>
                                            <p class="text-lg font-bold text-green-600">$<?= number_format($group['finalPrice']) ?></p>
                                        <?php else: ?>
                                            <p class="text-lg font-bold text-neutral-900">$<?= number_format($group['totalPrice']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" onclick="document.getElementById('<?= $groupId ?>').classList.toggle('hidden')" class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700 transition">
                                        <span>Details</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </div>

                                <!-- Details (collapsible) -->
                                <div id="<?= $groupId ?>" class="hidden mt-4 space-y-3 border-t border-neutral-200/60 pt-4">
                                    <?php foreach ($group['bookings'] as $b): ?>
                                        <?php
                                        $bdRaw = $b->booking_date;
                                        $dateDisplay = $bdRaw instanceof \DateTimeInterface
                                            ? $bdRaw->format('D, j M Y')
                                            : h((string)$bdRaw);
                                        $statusPill = match ($b->status) {
                                            'confirmed' => 'bg-primary-50 text-primary-900 ring-primary-100',
                                            'pending' => 'bg-amber-50 text-amber-900 ring-amber-100',
                                            'cancelled' => 'bg-red-50 text-red-800 ring-red-100',
                                            'rejected' => 'bg-neutral-100 text-neutral-700 ring-neutral-200',
                                            default => 'bg-neutral-100 text-neutral-700 ring-neutral-200',
                                        };
                                        ?>
                                        <?php
                                        $qty = $b->quantity ?? 1;
                                        $seatLabel = $qty == 1 ? '1 seat' : $qty . ' seats';
                                        ?>
                                        <div class="flex items-center justify-between gap-3 text-sm">
                                            <div class="min-w-0">
                                                <p class="font-medium text-neutral-900"><?= h($b->workshop->workshop_name ?? 'Workshop') ?> <span class="text-neutral-500">(<?= $seatLabel ?>)</span></p>
                                                <p class="text-neutral-500"><?= h($dateDisplay) ?></p>
                                            </div>
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold ring-1 <?= h($statusPill) ?>">
                                                <?= h(ucfirst((string)$b->status)) ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>

                                    <!-- Actions for group -->
                                    <div class="mt-4 flex flex-wrap items-center gap-2 pt-3 border-t border-neutral-200/40">
                                        <?php if ($group['paymentStatus'] !== 'paid' && $group['checkoutGroup']): ?>
                                            <?= $this->Html->link('Pay total', ['controller' => 'Bookings', 'action' => 'payGroup', $group['checkoutGroup']], [
                                                'class' => 'inline-flex items-center justify-center rounded-full bg-primary-600 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-700 transition'
                                            ]) ?>
                                        <?php endif; ?>

                                        <?php foreach ($group['bookings'] as $b): ?>
                                            <?php if ($b->status === 'pending'): ?>
                                                <?= $this->Form->postLink('Cancel #' . $b->id, ['controller' => 'Bookings', 'action' => 'cancel', $b->id], [
                                                    'confirm' => 'Cancel this booking?',
                                                    'class' => 'inline-flex items-center justify-center rounded-full border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50 transition',
                                                ]) ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Extra links (nếu cần mở rộng sau) -->
            <div class="mt-5 text-center space-y-4 text-sm font-serif text-neutral-600">
                <p>
                    Want to change your password? 
                    <?= $this->Html->link('Reset Password', ['controller' => 'Users', 'action' => 'forgotPassword'], [
                        'class' => 'text-primary-600 hover:text-primary-700 transition-colors font-medium'
                    ]) ?>
                </p>
                <p>
                    Need help? Contact us at 
                    <?= $this->Html->link('CandleCraftAcademy@gmail.com', 'mailto:CandleCraftAcademy@gmail.com', [
                        'class' => 'text-primary-600 hover:text-primary-700 transition-colors'
                    ]) ?>
                </p>
            </div>
        </div>
    </div>
</main>
