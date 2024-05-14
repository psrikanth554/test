<?php include("config.php") ?>
<!-- UIkit CSS -->
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/uikit@3.20.10/dist/css/uikit.min.css"/>

<?php
$invalidMessage = [];
$success = "";
if (isset($_POST) && $_SERVER['REQUEST_METHOD'] === "POST") {

    $name        = trim(htmlspecialchars(stripcslashes($_POST['fullname'])));
    $email       = trim(htmlspecialchars(stripcslashes($_POST['email'])));
    $phoneNumber = trim(htmlspecialchars(stripcslashes($_POST['phone_number'])));
    $message     = trim(htmlspecialchars(stripcslashes($_POST['message'])));
    $subject     = trim(htmlspecialchars(stripcslashes($_POST['subject'])));
    $message     = trim(htmlspecialchars(stripcslashes($_POST['message'])));

    if (! isset($name) || ! preg_match("/^[a-zA-Z ]*$/", $name)) {
        $invalidMessage[] = 'Invalid Name';
    }

    $emailPattern = '/^[a-z0-9]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i';

    if (! isset($email) || ! preg_match($emailPattern, $email)) {
        $invalidMessage[] = 'Invalid Email';

    }

    if (! isset($_POST['phone_number']) || ! preg_match("/^[0-9]*$/", $phoneNumber)) {
        $invalidMessage[] = 'Invalid Phone number';

    }

    if (! $invalidMessage) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $conn->query("insert into contactus (name,phone_number,email,subject,message, ip, created) 
values ('$name','$phoneNumber','$email','$subject','$message', '$ipAddress',  CURRENT_TIMESTAMP())");
        email($email, $subject, $message);

        header("Location: index.php?type=success");
    }

}

if ($invalidMessage) {
    foreach ($invalidMessage as $message) {
        echo "<div class='uk-alert uk-alert-danger'>$message</div>";
    }
}

if ($_GET['type']=== "success") {
    echo "<div class='uk-alert uk-alert-success'>Enquiry Sent Successfully</div>";
}

?>

<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post"
      class="uk-form">
    <div class="uk-container">
        <h2 class="uk-text-center">Enquiry Form</h2>
        <div class="uk-width-1-1">
            <div class="uk-width-1-2">Full Name:</div>
            <div class="uk-width-1-2 uk-float-left"><input class="uk-input"
                                                           type="text"
                                                           value="<?php echo isset($_POST['fullname']) ? $_POST['fullname'] : '' ?>"
                                                           name="fullname"
                                                           required/></div>
        </div>
        <div class="uk-width-1-1">
            <div class="uk-width-1-2">Phone Number:</div>
            <div class="uk-width-1-2 uk-float-left"><input class="uk-input"
                                                           type="tel"
                                                           value="<?php echo isset($_POST['phone_number']) ? $_POST['phone_number'] : '' ?>"
                                                           name="phone_number"
                                                           required/></div>
        </div>
        <div class="uk-width-1-1">
            <div class="uk-width-1-2">Email:</div>
            <div class="uk-width-1-2 uk-float-left"><input class="uk-input"
                                                           type="email"
                                                           name="email"
                                                           value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>"
                                                           required/></div>
        </div>
        <div class="uk-width-1-1">
            <div class="uk-width-1-2">Subject:</div>
            <div class="uk-width-1-2 uk-float-left"><input class="uk-input"
                                                           type="text"
                                                           value="<?php echo isset($_POST['subject']) ? $_POST['subject'] : '' ?>"
                                                           name="subject"
                                                           required/></div>
        </div>
        <div class="uk-width-1-1">
            <div class="uk-width-1-2">Message:</div>
            <div class="uk-width-1-2"><textarea class="uk-textarea"
                                                name="message"><?php echo isset($_POST['message']) ? $_POST['message'] : '' ?></textarea>
            </div>
        </div>
        <div class="uk-width-1-1">
            <div class="uk-width-1-2 uk-float-left"><input class="uk-button"
                                                           type="submit"
                                                           value="submit"/>
            </div>
        </div>
    </div>
</form>
<!-- UIkit JS -->
<script
    src="https://cdn.jsdelivr.net/npm/uikit@3.20.10/dist/js/uikit.min.js"></script>
<script
    src="https://cdn.jsdelivr.net/npm/uikit@3.20.10/dist/js/uikit-icons.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>


