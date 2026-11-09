{{--
    Full-screen loader between pages. Shown when leaving for another page of the app (a link or a
    plain form), only if the next page takes longer than the delay; never for Livewire requests or
    links with a `download` attribute. When the browser never leaves the page (a cancelled "leave
    page?" prompt, a response that downloads a file), a click on it or Escape hides it, and it hides
    itself after 10 s.

    The symbol's elements with class `fsl-loader-piece` come in one after the other.
--}}
<div id="fsl-page-loader" class="fsl-page-loader" role="status" aria-live="polite" aria-hidden="true" data-delay="{{ $delay }}">
    @if (filled($symbol))
        <div class="fsl-loader-symbol" aria-hidden="true">
            {{ $symbol }}
        </div>
    @else
        {{-- Generic symbol when the app gives none: three dots rising one after the other. --}}
        <div class="fsl-loader-symbol fsl-loader-symbol--generic" aria-hidden="true">
            <svg viewBox="0 0 120 32" width="60" height="16" fill="currentColor" focusable="false">
                <circle class="fsl-loader-piece" cx="16" cy="16" r="12"/>
                <circle class="fsl-loader-piece" cx="60" cy="16" r="12"/>
                <circle class="fsl-loader-piece" cx="104" cy="16" r="12"/>
            </svg>
        </div>
    @endif
    <span class="fsl-loader-label">{{ $label }}</span>
</div>

<script
    src="{{ \Filament\Support\Facades\FilamentAsset::getScriptSrc('page-loader', \MortalKiller\FilamentSkeletonLoading\SkeletonLoadingPlugin::PACKAGE) }}"
    @if ($nonce = \Illuminate\Support\Facades\Vite::cspNonce()) nonce="{{ $nonce }}" @endif
    data-navigate-once
></script>
