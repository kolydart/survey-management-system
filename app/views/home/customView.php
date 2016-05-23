<?php 
$router = new gateweb\mvc\core\Router();
?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Home</title>
</head>
<body>
	<h1>Welcome</h1>
	<p>Hello <?php echo $router->sanitize($name, 'html'); ?>!</p>

	<ul>
		<?php foreach ($colours as $colour): ?>
			<li><?php echo $router->sanitize($colour, 'html'); ?></li>
		<?php endforeach; ?>
	</ul>
</body>
</html>
