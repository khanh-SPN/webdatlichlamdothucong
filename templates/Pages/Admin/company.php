<?php $this$this->assign('title', 'Công ty'); ?>

<main class="min-h-screen pt-16 md:pt-20 pb-20 bg-neutral-50">
    <div class="max-w-4xl mx-auto px-3 lg:px-4">

        <!-- Heading with animation -->
        <div class="text-center mb-3 animate-fade-in">
            <h1 class="text-lg md:text-lg font-serif font-semibold text-neutral-800 tracking-tight">
                Thông tin Công ty
            </h1>
            <p class="mt-4 text-lg text-neutral-600 max-w-2xl mx-auto">
                Cập nhật các chi tiết cốt lõi của học viện của bạn, hiển thị trên các trang công khai và thông tin liên hệ.
            </p>
        </div>

        <!-- Form card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-neutral-200/60 p-4 md:p-5 transition-all duration-300 hover:shadow-2xl">
            
            <?= $this->Form->create($company, [
                'class' => 'space-y-4'
            ]) ?>

            <!-- Name -->
            <div class="space-y-2">
                <label class="block text-lg font-medium text-neutral-700">
                    Tên Công ty <span class="text-red-500">*</span>
                </label>
                <?= $this->Form->text('name', [
                    'class' => 'w-full px-5 py-4 rounded-xl border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all duration-300 text-lg bg-white/60',
                    'placeholder' => 'Hội Nghệ Thuật Nến',
                    'required'
                ]) ?>
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <label class="block text-lg font-medium text-neutral-700">
                    Email Liên hệ <span class="text-red-500">*</span>
                </label>
                <?= $this->Form->email('email', [
                    'class' => 'w-full px-5 py-4 rounded-xl border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all duration-300 text-lg bg-white/60',
                    'placeholder' => 'HoiNgheThuatNen@gmail.com',
                    'required'
                ]) ?>
            </div>

            <!-- Phone -->
            <div class="space-y-2">
                <label class="block text-lg font-medium text-neutral-700">
                    Số điện thoại
                </label>
                <?= $this->Form->tel('phone', [
                    'class' => 'w-full px-5 py-4 rounded-xl border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all duration-300 text-lg bg-white/60',
                    'placeholder' => '+61 412 345 678',
                    'inputmode' => 'tel',
                    'autocomplete' => 'tel',
                    'aria-describedby' => 'company-phone-help',
                ]) ?>
                <p id="company-phone-help" class="text-sm text-neutral-500">
                    Format example: <span class="font-medium text-neutral-700">+61 412 345 678</span>
                </p>
                <?php
                $phoneErr = $company?->getError('phone') ?? [];
                $phoneMsg = $phoneErr ? (string) (is_array(reset($phoneErr)) ? reset(reset($phoneErr)) : reset($phoneErr)) : '';
                if ($phoneMsg !== '') :
                    ?>
                    <p class="text-sm font-medium text-red-600" role="alert"><?= h($phoneMsg) ?></p>
                <?php endif; ?>
            </div>

            <!-- Address -->
            <div class="space-y-2">
                <label class="block text-lg font-medium text-neutral-700">
                    Address
                </label>
                <?= $this->Form->text('address', [
                    'class' => 'w-full px-5 py-4 rounded-xl border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all duration-300 text-lg bg-white/60',
                    'placeholder' => '123 Artisan Lane, Hoan Kiem, Hanoi'
                ]) ?>
            </div>

            <!-- Description -->
            <div class="space-y-2">
                <label class="block text-lg font-medium text-neutral-700">
                    Description / About
                </label>
                <?= $this->Form->textarea('description', [
                    'class' => 'w-full px-5 py-4 rounded-xl border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all duration-300 text-lg bg-white/60 min-h-[140px] resize-y',
                    'placeholder' => 'CandleCraft Academy offers premium workshops in pottery, knitting, and candle making...',
                    'rows' => 5
                ]) ?>
            </div>

            <!-- Submit -->
            <div class="pt-6 text-center md:text-right">
                <?= $this->Form->button('Save Changes', [
                    'type' => 'submit',
                    'class' => 'inline-flex items-center px-5 py-4 text-lg font-semibold rounded-full bg-primary-500 text-white hover:bg-primary-600 focus:ring-4 focus:ring-primary-200 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5'
                ]) ?>
            </div>

            <?= $this->Form->end() ?>

        </div>

    </div>
</main>

