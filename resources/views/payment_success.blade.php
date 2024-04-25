<style>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f5f5f5;
    background-image: linear-gradient(to bottom right, #ff6b6b, #3a1c71);
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    padding: 30px;
    background-color: #ffffff;
    text-align: center;
    color: #333333;
}

.card-header {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333333;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.btn-primary {
    background-color: #ff6b6b;
    border-color: #ff6b6b;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    margin-top: 20px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #00cc00;
    border-color: #00cc00;
}
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">🎉 Payment Success 🎉</div>
                <div class="card-body text-center">
                    <p>{{ $message }}</p>
                    <a href="http://localhost:3000/" class="btn btn-primary">🏠 Return to Home Page</a>
                </div>
            </div>
        </div>
    </div>
</div>

