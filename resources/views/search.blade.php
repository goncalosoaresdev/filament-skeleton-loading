<div
    wire:loading.delay
    wire:target="search"
    aria-hidden="true"
    class="fsl-global-search-loading"
    x-data="{ dismissed: false }"
    x-on:input.window="if ($event.target.matches('.fi-global-search input[type=search]')) dismissed = false"
    x-on:keydown.escape.window="dismissed = true"
>
    <div class="fsl-skeleton" x-show="! dismissed">
        <span class="fsl-bone fsl-line"></span>
        <span class="fsl-bone fsl-line"></span>
        <span class="fsl-bone fsl-line"></span>
    </div>
</div>
