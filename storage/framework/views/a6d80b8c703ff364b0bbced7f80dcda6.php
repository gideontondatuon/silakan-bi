<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SILAKAN — Sistem Informasi Layanan Kantor KPwBI Provinsi Sulawesi Utara">
    <meta name="theme-color" content="#005baa">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>SILAKAN | KPwBI Prov. Sulut</title>

    
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo-bi2.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('images/logo-bi2.png')); ?>">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/silakan.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bi-theme.css')); ?>">

    
    <?php echo app('Illuminate\Foundation\Vite')->reactRefresh(); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/react/main.tsx']); ?>

    <script>
        window.Laravel = {
            csrfToken: '<?php echo e(csrf_token()); ?>',
            baseUrl: '<?php echo e(url('/')); ?>'
        };
    </script>
</head>
<body style="margin:0;padding:0;background:#f8fafc;font-family:'Plus Jakarta Sans',sans-serif;color:#1e293b;">
    <div id="root"></div>
</body>
</html>
<?php /**PATH D:\Bank Indo\silakan\resources\views/react-app.blade.php ENDPATH**/ ?>