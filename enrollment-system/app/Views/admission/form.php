<h2>Admission Form</h2>
<form method="post" action="/admission/submit">
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="grade_level" required>
        <option value="7">Grade 7</option>
        <option value="8">Grade 8</option>
        <option value="9">Grade 9</option>
        <option value="10">Grade 10</option>
        <option value="11">Grade 11</option>
        <option value="12">Grade 12</option>
    </select>
    <select name="admission_type" required>
        <option value="regular">Regular</option>
        <option value="transferee">Transferee</option>
        <option value="reenroll">Re-Enroll</option>
    </select>
    <select name="strand_id">
        <option value="">-- Select Strand (if SHS) --</option>
        <option value="1">STEM</option>
        <option value="2">ABM</option>
        <option value="3">HUMSS</option>
        <option value="4">TVL</option>
    </select>
    <button type="submit">Submit</button>
</form>
