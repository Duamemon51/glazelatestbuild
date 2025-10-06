<script>
// Ensure functions are defined globally
window.toggleAllPatterns = function(fieldPrefix) {
    // Find all pattern checkboxes within the specific form context
    let formSelector;
    if (fieldPrefix === 'edit') {
        formSelector = '#editProductForm';
    } else if (fieldPrefix === 'createModal') {
        formSelector = '#createModalForm';
    } else {
        formSelector = `#${fieldPrefix}Form`;
    }
    
    const form = document.querySelector(formSelector) || document;
    const patternCheckboxes = form.querySelectorAll('input[name="patterns[]"].pattern-checkbox');
    
    if (patternCheckboxes.length === 0) return;
    
    // Determine if we should check all or uncheck all
    // If any are unchecked, check all. If all are checked, uncheck all.
    const checkedCount = Array.from(patternCheckboxes).filter(cb => cb.checked).length;
    const shouldCheck = checkedCount < patternCheckboxes.length;
    
    // Apply the new state to all checkboxes
    patternCheckboxes.forEach(checkbox => {
        checkbox.checked = shouldCheck;
    });
};

window.toggleAllDesigns = function(fieldPrefix) {
    // Find all design checkboxes within the specific form context
    let formSelector;
    if (fieldPrefix === 'edit') {
        formSelector = '#editProductForm';
    } else if (fieldPrefix === 'createModal') {
        formSelector = '#createModalForm';
    } else {
        formSelector = `#${fieldPrefix}Form`;
    }
    
    const form = document.querySelector(formSelector) || document;
    const designCheckboxes = form.querySelectorAll('input[name="designs[]"].design-checkbox');
    
    if (designCheckboxes.length === 0) return;
    
    // Determine if we should check all or uncheck all
    // If any are unchecked, check all. If all are checked, uncheck all.
    const checkedCount = Array.from(designCheckboxes).filter(cb => cb.checked).length;
    const shouldCheck = checkedCount < designCheckboxes.length;
    
    // Apply the new state to all checkboxes
    designCheckboxes.forEach(checkbox => {
        checkbox.checked = shouldCheck;
    });
};
</script>