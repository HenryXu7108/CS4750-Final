document.addEventListener('DOMContentLoaded', function() {
    var transactionTypeSelect = document.getElementById('transactionsType');
    var incomeFields = document.getElementById('incomeFields');
    var expenseFields = document.getElementById('expenseFields');
    var investmentFields = document.getElementById('investmentFields');
    var transactionFields = document.getElementById('transactionFields');
    var submitBtn = document.getElementById('submitBtn');


    transactionTypeSelect.addEventListener('change', function() {
       
        hideAllFields();

        if (this.value === 'Income') {
            incomeFields.style.display = 'block';
        } else if (this.value === 'Expense') {
            expenseFields.style.display = 'block';
        } else if (this.value === 'Investment') {
            investmentFields.style.display = 'block';
        } else if (this.value === 'Transaction') {
            transactionFields.style.display = 'block';
        }
        
        if (this.value) {
            submitBtn.style.display = 'block';
        }
    });

    function hideAllFields() {
        incomeFields.style.display = 'none';
        expenseFields.style.display = 'none';
        investmentFields.style.display = 'none';
        transactionFields.style.display = 'none';
        submitBtn.style.display = 'none';
    }
});
