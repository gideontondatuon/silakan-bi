@props([
    'title',
    'value',
    'icon'  => 'grid',
    'color' => 'blue',
    'trend' => null,
    'trendLabel' => null,
])

<div class="stat-card stat-card-{{ $color }}">
    <div class="stat-header">
        <div class="stat-title">{{ $title }}</div>
        <div class="stat-icon">
            <i class="bi bi-{{ $icon }}"></i>
        </div>
    </div>
    <div class="stat-value">{{ number_format($value) }}</div>
    <div class="stat-footer">
        @if($trend !== null)
            @if($trend >= 0)
                <i class="bi bi-arrow-up-short" style="color:#10b981;font-size:14px;"></i>
                <span style="color:#10b981;font-weight:700;">+{{ $trend }}</span>
            @else
                <i class="bi bi-arrow-down-short" style="color:#ef4444;font-size:14px;"></i>
                <span style="color:#ef4444;font-weight:700;">{{ $trend }}</span>
            @endif
            <span>{{ $trendLabel ?? 'vs bulan lalu' }}</span>
        @else
            <i class="bi bi-info-circle" style="font-size:12px;"></i>
            <span>Total keseluruhan</span>
        @endif
    </div>
</div>