<?php
/**
 * Shared HTML head section
 */
function renderHead($title = 'Dashboard') {
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?> — LiquidPanel</title>
<!-- Remix Icon CDN -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<!-- Liquid Glass CSS -->
<link rel="stylesheet" href="assets/css/liquid-glass.css">
<link rel="stylesheet" href="assets/css/animations.css">
<link rel="stylesheet" href="assets/css/responsive.css">
<style>body{font-family:'Inter',sans-serif;}</style>
<?php
}
?>
