function filterIDInput(input) {
    input.value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
}

document.getElementById('criteriaId').addEventListener('input', function() {
    filterIDInput(this);
});