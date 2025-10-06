@php
    $fieldPrefix = $fieldPrefix ?? 'create';
    $functionPrefix = ucfirst($fieldPrefix);
    $containerId = $containerId ?? ($fieldPrefix . '-pricing-tiers');
@endphp
<script>
function add{{ $functionPrefix }}PricingTier(minQty = '', price = '') {
    const container = document.getElementById('{{ $containerId }}');
    if (!container) {
        return;
    }
    const nextIndex = container.querySelectorAll('[data-pricing-row]').length + 1;
    const wrapper = document.createElement('div');
    wrapper.className = 'flex items-center space-x-2';
    wrapper.id = '{{ $fieldPrefix }}-tier-' + nextIndex;
    wrapper.setAttribute('data-pricing-row', 'true');
    wrapper.innerHTML = `
        <input type="number" name="prices[${nextIndex}][min_quantity]" value="${minQty}" placeholder="Min Qty" min="1"
               class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
        <input type="number" name="prices[${nextIndex}][price]" value="${price}" placeholder="Price" step="0.01" min="0"
               class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
        <button type="button" onclick="remove{{ $functionPrefix }}PricingTier(${nextIndex})"
                class="text-red-500 hover:text-red-700">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(wrapper);
}

function remove{{ $functionPrefix }}PricingTier(tierId) {
    const row = document.getElementById('{{ $fieldPrefix }}-tier-' + tierId);
    if (row) {
        row.remove();
    }
}
</script>
