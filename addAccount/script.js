document.addEventListener('DOMContentLoaded', function() {
    var addCardSelect = document.getElementById('addCard');
    var cardDetailsDiv = document.getElementById('cardDetails');
    var cardTypeSelect = document.getElementById('cardType'); 
    var creditCardDetailsDiv = document.getElementById('creditCardDetails'); 

    addCardSelect.addEventListener('change', function() {
        if (this.value === 'YES') {
            cardDetailsDiv.style.display = 'block';
        } else {
            cardDetailsDiv.style.display = 'none';
            creditCardDetailsDiv.style.display = 'none'; 
        }
    });

    cardTypeSelect.addEventListener('change', function() {
        if (this.value === 'CREDIT') {
            creditCardDetailsDiv.style.display = 'block';
        } else {
            creditCardDetailsDiv.style.display = 'none';
        }
    });
});
