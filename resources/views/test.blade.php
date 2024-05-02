<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>
<body>
<form action="{{ url('upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <button type="submit">Загрузить</button>
</form>

<p>
    <?php echo print_r($file, true) ?>
    <?php echo print_r($_POST, true) ?>
</p>

</body>
</html>
