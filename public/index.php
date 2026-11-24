<?php
declare(strict_types=1);

/**
 * ITCS333 A4 starter — contact form.
 * Requirements (see README rubric):
 *   - GET: render the form
 *   - POST: validate name (required, 2-60 chars), email (valid), message (required, 10-1000 chars)
 *   - invalid POST: re-render with inline errors + repopulated fields (200)
 *   - valid POST: redirect (302/303) to a success page (PRG)
 *   - escape all output with htmlspecialchars
 */

require_once __DIR__ . '/../src/validate.php';

// TODO: implement the form handling here.
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$values = [
    'name' => '',
    'email' => '',
    'message' => '',
];

$errors = [
    'name' => '',
    'email' => '',
    'message' => '',
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $values['name'] = trim((string) ($_POST['name'] ?? ''));
    $values['email'] = trim((string) ($_POST['email'] ?? ''));
    $values['message'] = trim((string) ($_POST['message'] ?? ''));

    if (!validate_required($values['name'])) {
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length($values['name'], 2, 60)) {
        $errors['name'] = 'Name must be between 2 and 60 characters.';
    }

    if (!validate_required($values['email'])) {
        $errors['email'] = 'Email is required.';
    } elseif (!validate_email($values['email'])) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (!validate_required($values['message'])) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length($values['message'], 10, 1000)) {
        $errors['message'] = 'Message must be between 10 and 1000 characters.';
    }

    $hasErrors = $errors['name'] !== '' || $errors['email'] !== '' || $errors['message'] !== '';

    if (!$hasErrors) {
        header('Location: success.php', true, 302);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us</title>
</head>
<body>
  <h1>Contact Us</h1>
  <!-- TODO: render the form, inline errors, and repopulated values -->
  <form method="post" action="index.php">
    <div>
      <label for="name">Name</label><br>
      <input
        id="name"
        type="text"
        name="name"
        value="<?= e($values['name']) ?>"
      >
      <?php if ($errors['name'] !== ''): ?>
        <p class="error"><?= e($errors['name']) ?></p>
      <?php endif; ?>
    </div>

    <div>
      <label for="email">Email</label><br>
      <input
        id="email"
        type="email"
        name="email"
        value="<?= e($values['email']) ?>"
      >
      <?php if ($errors['email'] !== ''): ?>
        <p class="error"><?= e($errors['email']) ?></p>
      <?php endif; ?>
    </div>

    <div>
      <label for="message">Message</label><br>
      <textarea
        id="message"
        name="message"
      ><?= e($values['message']) ?></textarea>
      <?php if ($errors['message'] !== ''): ?>
        <p class="error"><?= e($errors['message']) ?></p>
      <?php endif; ?>
    </div>

    <button type="submit">Send</button>
  </form>
</body>
</html>

