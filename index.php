<?php if (!file_exists('./options.php')) { echo 'Error! Please generate an options.php file! See README for more information!'; exit(); } ?>
<?php $options = include 'options.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>NGINX config</title>
<style type="text/css">
body, html {
	background: #F2E1C4;
}

body {
	font-family: Arial;
	font-size: 12px;
	text-align: center;
	max-width: 800px;
	margin: auto;
}

#options {
	width: 100%;
	text-align: left;
	background: #D8C7AA;
	border: 2px solid #E51937;
}

#options td {
	text-align: left;
}

#options th {
	text-align: center;
	font-size: 1.2em;
}

#configure {
	background: #FDBA31;
	border: 2px solid #F68428;
	margin-bottom: 8px;
	padding: 2px;
	font-family: monospace;
	text-align: left;
}
</style>
</head>
<body>
<h1>NGINX ./configure argument generator</h1>
<?php if (is_array($_GET) && count($_GET)) : ?>
<?php

$configure = './configure ';
foreach ($_GET as $k => $v) {
	if (!preg_match('#^(.+)_enabled$#', $k, $m)) { continue; }
	if (!isset($_GET['oneline'])) { $configure .= "\\\n"; }
	$configure .= '--'.$m[1];
	if (isset($_GET[$m[1].'_value'])) {
		$configure .= sprintf('="%s"', str_replace('"', '\\"', $_GET[$m[1].'_value']));
	}
	$configure .= ' ';
}
$configure = substr($configure, 0, -1);

?>
<div id="configure">
<?php echo nl2br(htmlspecialchars($configure)); ?>
<br /><br />
<?php if (isset($_GET['oneline'])) : ?>
<div>Or as <a href="<?php echo htmlspecialchars(preg_replace('#(&oneline=?|oneline=?&)#', '', $_SERVER['REQUEST_URI'])); ?>">multiple lines</a></div>
<?php else : ?>
<div>Or as <a href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'].'&oneline'); ?>">one line</a></div>
<?php endif; ?>
</div>
<?php endif; ?>
<form action="?generate" method="get">
<table id="options">
<tr>
	<th>&nbsp;</th>
	<th>Option Name</th>
	<th>Option Value</th>
	<th>Option Description</th>
</tr>
<?php foreach ($options as $option) : ?>
<?php if ($option['type'] == 'break') : ?>
<tr>
	<th colspan="4">&nbsp;</th>
</tr>
<?php else : ?>
<tr>
	<td>
		<input type="checkbox" id="<?php echo htmlspecialchars($option['name']); ?>_enabled" name="<?php echo htmlspecialchars($option['name']); ?>_enabled" <?php if (isset($_GET[$option['name'].'_enabled'])) { ?>checked="checked"<?php } ?>>
	</td>
	<td>
		<label for="<?php echo htmlspecialchars($option['name']); ?>_enabled"><?php echo htmlspecialchars($option['name']); ?></label>
	</td>
	<td>
<?php switch ($option['type']) { ?>
<?php case 'free': ?>
		<input type="text" name="<?php echo htmlspecialchars($option['name'], ENT_QUOTES); ?>_value" value="<?php if (isset($_GET[$option['name'].'_value'])) { echo htmlspecialchars($_GET[$option['name'].'_value'], ENT_QUOTES); } ?>">
<?php break; ?>
<?php case 'multi': ?>
		<select name="<?php echo htmlspecialchars($option['name'], ENT_QUOTES); ?>_value">
<?php foreach ($option['options'] as $o) : ?>
			<option value="<?php echo htmlspecialchars($o, ENT_QUOTES); ?>" <?php if (isset($_GET[$option['name'].'_value']) && ($_GET[$option['name'].'_value'] == $o)) { ?>selected="selected"<?php } ?>><?php echo htmlspecialchars($o); ?></option>
<?php endforeach; ?>
		</select>
<?php break; ?>
<?php default: ?>
		&nbsp;
<?php break; ?>
<?php } ?>
	</td>
	<td>
		<?php echo htmlspecialchars($option['description'])."\n"; ?>
	</td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
<tr>
	<th colspan="4">
		<input type="submit">
	</th>
</tr>
</table>
</form>
</body>
</html>
