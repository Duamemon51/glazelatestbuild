@php
    $fieldPrefix = $fieldPrefix ?? 'product';
    $hierarchy = collect($hierarchy ?? [])->map(function ($parent) {
        return [
            'id' => $parent['id'],
            'name' => $parent['name'],
            'subcategories' => collect($parent['subcategories'] ?? [])->map(function ($subcategory) {
                return [
                    'id' => $subcategory['id'],
                    'name' => $subcategory['name'],
                    'product_types' => collect($subcategory['product_types'] ?? [])->map(function ($type) {
                        return [
                            'id' => $type['id'],
                            'name' => $type['name'],
                        ];
                    })->values()->all(),
                ];
            })->values()->all(),
        ];
    })->values()->all();
@endphp
<script>
(function() {
    console.log('Hierarchy script loading for prefix:', @json($fieldPrefix));
    const hierarchy = @json($hierarchy);
    const prefix = @json($fieldPrefix);
    console.log('Parsed hierarchy:', hierarchy);
    console.log('Hierarchy is array:', Array.isArray(hierarchy));
    console.log('Hierarchy length:', hierarchy ? hierarchy.length : 'null');
    if (hierarchy && hierarchy.length > 0) {
        console.log('First parent:', hierarchy[0]);
        console.log('First parent subcategories:', hierarchy[0].subcategories);
    }

    const parentSelect = document.getElementById(`${prefix}_parent_category_id`);
    const subcategorySelect = document.getElementById(`${prefix}_subcategory_id`);
    const productTypeSelect = document.getElementById(`${prefix}_product_type_id`);

    console.log('Selects found:', parentSelect, subcategorySelect, productTypeSelect);

    if (!parentSelect || !subcategorySelect || !productTypeSelect) {
        console.error('Some selects not found for prefix:', prefix);
        return;
    }

    if (!window.productHierarchyState) {
        window.productHierarchyState = {};
    }

    const state = window.productHierarchyState[prefix] || {};
    state.hierarchy = hierarchy;
    state.parentSelect = parentSelect;
    state.subcategorySelect = subcategorySelect;
    state.productTypeSelect = productTypeSelect;

    function makeOption(label, value) {
        const opt = document.createElement('option');
        opt.value = value;
        opt.textContent = label;
        return opt;
    }

    function populateSelect(select, items, placeholder, selectedValue = '') {
        const currentValue = selectedValue ?? select.value ?? '';
        select.innerHTML = '';

        const placeholderOption = makeOption(placeholder, '');
        if (!items.length) {
            placeholderOption.disabled = false;
        } else {
            placeholderOption.disabled = true;
        }
        if (!currentValue) {
            placeholderOption.selected = true;
        }
        select.appendChild(placeholderOption);

        items.forEach(item => {
            const opt = makeOption(item.name, String(item.id));
            if (String(item.id) === String(currentValue)) {
                opt.selected = true;
            }
            select.appendChild(opt);
        });

        if (items.length === 0) {
            select.value = '';
        }

        select.disabled = items.length === 0;
    }

    function findParent(id) {
        return hierarchy.find(parent => String(parent.id) === String(id));
    }

    function findSubcategories(parentId) {
        const parent = findParent(parentId);
        return parent ? parent.subcategories : [];
    }

    function findProductTypes(parentId, subcategoryId) {
        const subcategories = findSubcategories(parentId);
        const subcategory = subcategories.find(item => String(item.id) === String(subcategoryId));
        return subcategory ? subcategory.product_types : [];
    }

    function render(options = {}) {
        const reset = options === true || (options && options.reset === true);

        if (typeof parentSelect.dataset.initial === 'undefined') {
            parentSelect.dataset.initial = parentSelect.value || '';
        }
        if (typeof subcategorySelect.dataset.initial === 'undefined') {
            subcategorySelect.dataset.initial = subcategorySelect.value || '';
        }
        if (typeof productTypeSelect.dataset.initial === 'undefined') {
            productTypeSelect.dataset.initial = productTypeSelect.value || '';
        }

        const parentValue = reset ? '' : (parentSelect.dataset.initial || parentSelect.value || '');
        populateSelect(parentSelect, hierarchy, 'Select Main Category', parentValue);

        const effectiveParent = parentSelect.value || '';
        const subcategories = effectiveParent ? findSubcategories(effectiveParent) : [];
        const subcategoryValue = reset ? '' : (subcategorySelect.dataset.initial || subcategorySelect.value || '');
        populateSelect(subcategorySelect, subcategories, 'Select Subcategory', subcategoryValue);

        const effectiveSubcategory = subcategorySelect.value || '';
        const productTypes = (effectiveParent && effectiveSubcategory) ? findProductTypes(effectiveParent, effectiveSubcategory) : [];
        const productTypeValue = reset ? '' : (productTypeSelect.dataset.initial || productTypeSelect.value || '');
        populateSelect(productTypeSelect, productTypes, 'Select Product Type', productTypeValue);
    }

    function handleParentChange(evt) {
        console.log('Parent changed to:', evt?.target?.value ?? parentSelect.value);
        const selectedParent = evt?.target?.value ?? parentSelect.value;
        const subcategories = selectedParent ? findSubcategories(selectedParent) : [];
        console.log('Found subcategories:', subcategories);
        populateSelect(subcategorySelect, subcategories, 'Select Subcategory', '');
        populateSelect(productTypeSelect, [], 'Select Product Type', '');
    }

    function handleSubcategoryChange(evt) {
        const selectedParent = parentSelect.value;
        const selectedSubcategory = evt?.target?.value ?? subcategorySelect.value;
        const types = selectedParent && selectedSubcategory ? findProductTypes(selectedParent, selectedSubcategory) : [];
        populateSelect(productTypeSelect, types, 'Select Product Type', '');
    }

    parentSelect.addEventListener('change', handleParentChange);
    subcategorySelect.addEventListener('change', handleSubcategoryChange);

    state.populate = render;
    window.productHierarchyState[prefix] = state;

    render();
})();
</script>
