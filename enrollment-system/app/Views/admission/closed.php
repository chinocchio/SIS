<div class="admission-closed">
    <h2>Admission is Currently Closed</h2>
    <div class="message">
        <p>We're sorry, but the admission period is currently closed.</p>
        <p>Please check back later or contact the school administration for more information.</p>
    </div>
    
    <div class="actions">
        <a href="/" class="btn btn-primary">Return to Home</a>
        <a href="/student/login" class="btn btn-secondary">Student Login</a>
    </div>
</div>

<style>
.admission-closed {
    text-align: center;
    padding: 50px 20px;
    max-width: 600px;
    margin: 0 auto;
}

.admission-closed h2 {
    color: #dc3545;
    margin-bottom: 30px;
}

.message {
    background-color: #f8f9fa;
    padding: 30px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.message p {
    margin-bottom: 15px;
    font-size: 16px;
    color: #495057;
}

.actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 500;
    transition: background-color 0.3s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #545b62;
}
</style>
