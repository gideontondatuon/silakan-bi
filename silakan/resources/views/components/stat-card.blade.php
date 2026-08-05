@props([
    'title',
    'value',
    'icon' => 'grid'
])


<div class="stat-card">

    <div class="stat-header">

        <div class="stat-icon">

            <i class="bi bi-{{ $icon }}"></i>

        </div>


        <div class="stat-title">

            {{ $title }}

        </div>

    </div>


    <div class="stat-value">

        {{ $value }}

    </div>


    <div class="stat-footer">

        Sistem SILAKAN

    </div>

</div>