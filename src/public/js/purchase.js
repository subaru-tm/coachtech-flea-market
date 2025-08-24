const payment_method = document.getElementById('payment');
payment_method.addEventListener('change', function (e) {
    e.preventDefault();
    document.getElementById('pay_confirm').textContent = e.target.value == 'card' ? 'クレジットカード払い' : 'コンビニ払い';
});