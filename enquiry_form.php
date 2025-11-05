<?php
session_start();

// Generate a random captcha question (e.g. 4 + 7)
$first = rand(1, 10);
$second = rand(1, 10);
$_SESSION['captcha_answer'] = $first + $second;
?>

<div class="enquiry-form">
  <h3>Enquiry Form</h3>
  <form action="process_enquiry.php" method="POST">
    <label for="name">Name</label>
    <input type="text" name="name" required>

    <label for="email">Email</label>
    <input type="email" name="email" required>

    <label for="phone">Phone Number</label>
    <input type="tel" name="phone" pattern="[0-9]{10}" maxlength="10" required>

    <label for="whatsapp">WhatsApp Number</label>
    <input type="tel" name="whatsapp" pattern="[0-9]{10}" maxlength="10" required>

    <label for="destination">Destination</label>
    <input type="text" name="destination" required>

    <label for="travel_date">Travel Date</label>
    <input type="date" name="travel_date" required>

    <label for="message">Message</label>
    <textarea name="message" rows="3"></textarea>

    <label for="captcha">Captcha: What is <?= $first ?> + <?= $second ?> ?</label>
    <input type="text" name="captcha" required>

    <button type="submit" class="btn">Submit</button>
  </form>
</div>

<style>
.enquiry-form {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 320px;
}
.enquiry-form h3 {
  text-align: center;
  color: #0b4661;
  margin-bottom: 15px;
}
.enquiry-form input, .enquiry-form textarea {
  width: 100%;
  padding: 8px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
.enquiry-form .btn {
  background: #0b4661;
  color: #fff;
  border: none;
  padding: 8px 12px;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
}
.enquiry-form .btn:hover {
  background: #093547;
}
</style>
