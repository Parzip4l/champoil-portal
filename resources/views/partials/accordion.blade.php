<div class="accordion-item">
    <h2 class="accordion-header" id="heading-{{ $id }}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $id }}" aria-expanded="false" aria-controls="collapse-{{ $id }}">
            {{ $title }}
        </button>
    </h2>
    <div id="collapse-{{ $id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $id }}" data-bs-parent="#settingAccordion">
        <div class="accordion-body row g-3">
            {{ $slot }}
        </div>
    </div>
</div>
