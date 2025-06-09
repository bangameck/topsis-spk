<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.print.min.js"></script>

<script>
  new DataTable('#tableUsers', {
    layout: {
      top2Start: {
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
      },
      topEnd: {
        search: {
          placeholder: 'Cari Users'
        }
      }
    },
    scrollX: true,
    scrollY: 400,
    language: {
      entries: {
        _: 'users',
        1: 'user'
      }
    }
  });

  new DataTable('#tableCriteria', {
    layout: {

      topEnd: {
        search: {
          placeholder: 'Cari Keriteria'
        }
      }
    },
    scrollX: true,
    scrollY: 250,
    language: {
      entries: {
        _: 'criterias',
        1: 'criteria'
      }
    },
    autoWidth: false
  });
</script>