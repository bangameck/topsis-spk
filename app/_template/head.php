<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url(); ?>assets/demo/favicon.png">
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/pace.css">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<!-- <title>Default</title> -->
<!-- CSS -->
<link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600|Roboto:400" rel="stylesheet" type="text/css">
<link href="<?= base_url(); ?>assets/vendors/material-icons/material-icons.css" rel="stylesheet" type="text/css">
<link href="<?= base_url(); ?>assets/vendors/mono-social-icons/monosocialiconsfont.css" rel="stylesheet" type="text/css">
<link href="<?= base_url(); ?>assets/vendors/feather-icons/feather.css" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css" rel="stylesheet" type="text/css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"> -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.css" rel="stylesheet" type="text/css">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<!-- Head Libs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
<style>
  .drag-drop-area {
    min-height: 180px;
    /* Diperbesar sedikit dari contoh sebelumnya */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px dashed #ced4da;
    border-radius: 8px;
    /* Sedikit lebih membulat */
    padding: 20px;
    position: relative;
    overflow: hidden;
    /* Pastikan gambar preview tidak keluar */
  }

  .drag-drop-area:hover {
    border-color: #0d6efd;
    /* Warna hover yang lebih jelas */
    background-color: rgba(13, 110, 253, 0.05);
  }

  .drag-drop-area.highlight {
    border-color: #0d6efd;
    /* Highlight saat drag over */
    background-color: rgba(13, 110, 253, 0.1);
  }

  .preview-image {
    max-width: 100%;
    max-height: 150px;
    /* Batasi tinggi preview */
    object-fit: contain;
    /* Jaga rasio aspek */
    border-radius: 4px;
    /* Sudut sedikit membulat */
  }

  .clear-image-icon {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 24px;
    color: #dc3545;
    /* Merah untuk hapus */
    cursor: pointer;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    padding: 3px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    transition: all 0.2s ease;
  }

  .clear-image-icon:hover {
    color: #c82333;
    transform: scale(1.1);
  }

  .current-profile-image {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
  }

  /* Sembunyikan elemen teks saat ada preview gambar */
  .drag-drop-area.has-image .initial-text,
  .drag-drop-area.has-image button,
  .drag-drop-area.has-image .max-size-text {
    /* Sembunyikan juga teks max size saat ada gambar */
    display: none;
  }

  .current-profile-image {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
  }

  .current-image-label {
    /* Styling for the "Gambar saat ini" text */
    display: block;
    margin-top: 5px;
  }
</style>