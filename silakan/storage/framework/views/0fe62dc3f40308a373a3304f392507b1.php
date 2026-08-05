<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'value',
    'icon' => 'grid'
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
    'icon' => 'grid'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>


<div class="stat-card">

    <div class="stat-header">

        <div class="stat-icon">

            <i class="bi bi-<?php echo e($icon); ?>"></i>

        </div>


        <div class="stat-title">

            <?php echo e($title); ?>


        </div>

    </div>


    <div class="stat-value">

        <?php echo e($value); ?>


    </div>


    <div class="stat-footer">

        Sistem SILAKAN

    </div>

</div><?php /**PATH D:\Bank Indo\silakan\resources\views/components/stat-card.blade.php ENDPATH**/ ?>