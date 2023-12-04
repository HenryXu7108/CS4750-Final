document.addEventListener('DOMContentLoaded', function() {
    var addCardSelect = document.getElementById('addCard');
    var cardDetailsDiv = document.getElementById('cardDetails');

    addCardSelect.addEventListener('change', function() {
       
        if (this.value === 'YES') {
            cardDetailsDiv.style.display = 'block';
        } else {
            cardDetailsDiv.style.display = 'none';
        }
    });
});
