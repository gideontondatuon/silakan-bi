<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'value',
    'icon'  => 'grid',
    'color' => 'blue',
    'trend' => null,
    'trendLabel' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title',
    'value',
    'icon'  => 'grid',
    'color' => 'blue',
    'trend' => null,
    'trendLabel' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="stat-card stat-card-<?php echo e($color); ?>">
    <div class="stat-header">
        <div class="stat-title"><?php echo e($title); ?></div>
        <div class="stat-icon">
            <i class="bi bi-<?php echo e($icon); ?>"></i>
        </div>
    </div>
    <div class="stat-value"><?php echo e(number_format($value)); ?></div>
    <div class="stat-footer">
        <?php if($trend !== null): ?>
            <?php if($trend >= 0): ?>
                <i class="bi bi-arrow-up-short" style="color:#10b981;font-size:14px;"></i>
                <span style="color:#10b981;font-weight:700;">+<?php echo e($trend); ?></span>
            <?php else: ?>
                <i class="bi bi-arrow-down-short" style="color:#ef4444;font-size:14px;"></i>
                <span style="color:#ef4444;font-weight:700;"><?php echo e($trend); ?></span>
            <?php endif; ?>
            <span><?php echo e($trendLabel ?? 'vs bulan lalu'); ?></span>
        <?php else: ?>
            <i class="bi bi-info-circle" style="font-size:12px;"></i>
            <span>Total keseluruhan</span>
        <?php endif; ?>
    </div>
</div><?php /**PATH D:\Bank Indo\silakan\resources\views/components/stat-card.blade.php ENDPATH**/ ?>