<div class="fsl-skeleton fsl-deferred-table" aria-hidden="true" data-fsl-columns="{{ $columns }}">
    @if ($isCardLayout)
        <div {{ $attributes->class(['fsl-deferred-cards']) }}>
            @for ($card = 0; $card < 6; $card++)
                <div class="fsl-deferred-card">
                    <span class="fsl-bone fsl-title"></span>
                    <span class="fsl-bone fsl-line"></span>
                    <span class="fsl-bone fsl-line"></span>
                </div>
            @endfor
        </div>
    @else
        <div class="fsl-deferred-cells" style="--fsl-columns: {{ $columns }}">
            @for ($cell = 0; $cell < $columns * 7; $cell++)
                <span class="fsl-bone"></span>
            @endfor
        </div>
    @endif
</div>
