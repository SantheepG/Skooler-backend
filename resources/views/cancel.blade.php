<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-family: Arial, sans-serif;
        background-image: linear-gradient(to bottom right, #F0B27A, #AF7AC5);
        border: 3px solid #ccc;
    }
    .container {
        text-align: center;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    h1 {
        color: red;
        font-size: 24px;
        margin-bottom: 10px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }
    p {
        color: #777;
        font-size: 16px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }
    .container:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease-in-out;
    }
</style>

<div class="container">
    <h1>Payment Cancelled</h1>
    <p>Your payment has been cancelled. You will be redirected to the home page in a few seconds.</p>
    <p id="countdown"></p>
</div>

<script>
    // Countdown timer
    var countdownElement = document.getElementById('countdown');
    var countdownTime = 5; // Countdown time in seconds

    function updateCountdown() {
        countdownElement.innerHTML = countdownTime + ' seconds remaining';
        countdownTime--;

        if (countdownTime < 0) {
            window.location.href = 'http://localhost:3000/';
        } else {
            setTimeout(updateCountdown, 1000);
        }
    }

    updateCountdown();
</script>
